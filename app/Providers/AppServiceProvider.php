<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Report;
use App\Observers\ReportObserver;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Report::observe(ReportObserver::class);

        // D18 / F29 / F31 : alertes en bandeau sur toutes les pages publiques
        View::composer('layouts.public', function ($view) {
            $view->with('alerts', Schema::hasTable('announcements')
                ? Announcement::banner()->take(3)->get()
                : collect());
        });
        RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by($request->email . $request->ip())->response(function () {
            return response('Trop de tentatives de connexion. Veuillez réessayer dans une minute.', 429);
        });
    });

    // Limiter les soumissions de formulaires publics (ex: RDV / Contact : 3 par minute)
    RateLimiter::for('contact-form', function (Request $request) {
        return Limit::perMinute(3)->by($request->ip());
    });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
