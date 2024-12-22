<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\PusherCredentail;
class PusherConfig extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->booted(function () {
        //     $shouldSetCustomConfig = true;
        //     if($shouldSetCustomConfig) {
                
        //         $pusher = PusherCredentail::first();

        //         $pusherConfig = [
        //             'driver' => 'pusher',
        //             'key' => $pusher->app_key,
        //             'secret' => $pusher->app_secret,
        //             'app_id' => $pusher->app_id,
        //             'options' => [
        //                 'cluster' => $pusher->app_cluster,
        //                 'useTLS' => true,
        //                 'encrypted' => true
        //             ],
        //         ];
                
        //         // config(['broadcasting.connections.pusher' => $pusherConfig]);
        //     }
        // });


        $this->app->booted(function () {
            // Ensure the table exists before querying it
            if (Schema::hasTable('pusher_credentails')) {
                $pusher = PusherCredentail::first();

                if ($pusher) {
                    $pusherConfig = [
                        'driver' => 'pusher',
                        'key' => $pusher->app_key,
                        'secret' => $pusher->app_secret,
                        'app_id' => $pusher->app_id,
                        'options' => [
                            'cluster' => $pusher->app_cluster,
                            'useTLS' => true,
                            'encrypted' => true,
                        ],
                    ];

                    // Apply the custom Pusher configuration
                    config(['broadcasting.connections.pusher' => $pusherConfig]);
                }
            }
        });

    }
}
