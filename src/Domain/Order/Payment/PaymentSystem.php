<?php

namespace Domain\Order\Payment;

use Closure;
use Domain\Order\Contracts\PaymentGatewayContract;
use Domain\Order\Exceptions\PaymentProcessException;
use Domain\Order\Exceptions\PaymentProviderException;
use Domain\Order\Models\Payment;
use Domain\Order\Models\PaymentHistory;
use Domain\Order\States\Payment\CancelledPaymentState;
use Domain\Order\States\Payment\PaidPaymentState;
use Domain\Order\Traits\PaymentEvents;

class PaymentSystem
{
    use PaymentEvents;

    protected static PaymentGatewayContract $provider;

    public static function provider(PaymentGatewayContract|Closure $providerOrClosure): void
    {
        if (is_callable($providerOrClosure)) {
            $providerOrClosure = call_user_func($providerOrClosure);
        }

        if (!$providerOrClosure instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        self::$provider = $providerOrClosure;
    }

    public static function create(PaymentData $data): PaymentGatewayContract
    {
        if (!self::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        Payment::query()->create([
            'payment_id' => $data->id,
            'amount' => $data->amount,
            'description' => $data->description,
            'returnUrl' => $data->returnUrl,
            'meta' => $data->meta,
        ]);

        if (is_callable(self::$onCreating)) {
            $data = call_user_func(self::$onCreating, $data);
        }

        return self::$provider->data($data);
    }

    public static function validate(): PaymentGatewayContract
    {
        if (!self::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        PaymentHistory::query()->create([
            'method' => request()->method(),
            'payload' => self::$provider->request(),
            'payment_gateway' => get_class(self::$provider),
        ]);

        if (is_callable(self::$onValidating)) {
            $data = call_user_func(self::$onValidating);
        }

        if (self::$provider->validate() && self::$provider->paid()) {
            try {
                $payment = Payment::query()
                    ->where('payment_id', self::$provider->paymentId())
                    ->firstOr(function () {
                        throw PaymentProcessException::paymentNotFound();
                    });

                if (is_callable(self::$onSuccess)) {
                    $data = call_user_func(self::$onSuccess, $payment);
                }

                $payment->state->transitionTo(PaidPaymentState::class);
            } catch (PaymentProcessException $e) {
                $payment->state->transitionTo(CancelledPaymentState::class);

                if (is_callable(self::$onError)) {
                    $data = call_user_func(
                        self::$onError,
                        self::$provider->errorMessage() ?? $e->getMessage()
                    );
                }
            }
        }

        return self::$provider;
    }
}
