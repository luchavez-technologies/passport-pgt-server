<?php

namespace Luchavez\PassportPgtServer\Providers;

use Luchavez\PassportPgtServer\Services\PassportPgtServer;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class PassportPgtServerServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class PassportPgtServerServiceProvider extends BaseStarterKitServiceProvider
{
    /**
     * Publishable Environment Variables
     *
     * @example [ 'HELLO_WORLD' => true ]
     *
     * @var array
     */
    protected array $env_vars = [
        'PPS_AT_EXPIRE_UNIT' => 'days',
        'PPS_AT_EXPIRE_VALUE' => 15,
        'PPS_RT_EXPIRE_UNIT' => 'days',
        'PPS_RT_EXPIRE_VALUE' => 30,
        'PPS_PAT_EXPIRE_UNIT' => 'days',
        'PPS_PAT_EXPIRE_VALUE' => 6,
        'PPS_HASH_CLIENT_SECRETS' => false,
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        // Add Passport Routes
        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }

        // Override Auth Config
        passportPgtServer()->setPassportAsApiDriver();

        // Hash Client Secrets
        if (passportPgtServer()->hashClientSecrets()) {
            Passport::hashClientSecrets();
        }

        // Set Expirations
        Passport::tokensExpireIn(passportPgtServer()->getTokensExpiresIn());
        Passport::refreshTokensExpireIn(passportPgtServer()->getRefreshTokensExpiresIn());
        Passport::personalAccessTokensExpireIn(passportPgtServer()->getPersonalAccessTokensExpiresIn());
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('passport-pgt-server', function ($app, $params) {
            return new PassportPgtServer(collect($params)->get('auth_server_controller'));
        });

        parent::register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['passport-pgt-server'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/passport-pgt-server.php' => config_path('passport-pgt-server.php'),
        ], 'passport-pgt-server.config');
    }
}
