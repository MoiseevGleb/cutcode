<?php

namespace App\Providers;

use App\Faker\FakerImageProvider;
use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(app()->isLocal());

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
