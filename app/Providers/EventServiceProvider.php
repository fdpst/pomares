<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\NroPresupuestoObserver;
use App\Models\NroPresupuesto;
use App\Observers\NroNotaObserver;
use App\Models\NroNota;
use App\Observers\NroFacturaObserver;
use App\Models\NroFactura;
use App\Observers\NroOrdenObserver;
use App\Models\NroOrden;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
       NroPresupuesto::observe(NroPresupuestoObserver::class);
       NroNota::observe(NroNotaObserver::class);
       // NroFactura::observe(NroFacturaObserver::class);
       // NroOrden::observe(NroOrdenObserver::class);
    }
}
