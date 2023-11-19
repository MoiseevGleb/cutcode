<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(app()->isLocal());

        $this->app->bind(TelegramBotApiContract::class, TelegramBotApi::class); 

        if (app()->isProduction()) {

            DB::listen(function ($query) {
                if ($query->time > 500) {
                    logger()
                        ->channel('telegram')
                        ->debug('Query is longer than 500ms: '. $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::second(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan: '. request()->url());
                }
            );
        }
    }
}
