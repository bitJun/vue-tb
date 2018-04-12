<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/8/21
 * Time: 下午7:50
 */

namespace App\Providers;


use App\Utils\Gaode;
use Illuminate\Support\ServiceProvider;

class GaodeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('gaode', function () {
            return new Gaode();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'gaode'
        ];
    }
}