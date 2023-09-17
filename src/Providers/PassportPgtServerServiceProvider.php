<?php

namespace Luchavez\PassportPgtServer\Providers;

use Laravel\Passport\Passport;
use Luchavez\PassportPgtServer\Console\Commands\InstallPassportPGTServerCommand;
use Luchavez\PassportPgtServer\Services\PassportPgtServer;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider;

/**
 * Class PassportPgtServerServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class PassportPgtServerServiceProvider extends BaseStarterKitServiceProvider
{
    protected array $commands = [
        InstallPassportPGTServerCommand::class,
    ];

    /**
     * Publishable Environment Variables
     *
     * @example [ 'HELLO_WORLD' => true ]
     *
     * @var array
     */
    protected array $env_vars = [
        'PASSPORT_ACCESS_TOKEN_EXPIRES_IN' => '15 days',
        'PASSPORT_REFRESH_TOKEN_EXPIRES_IN' => '30 days',
        'PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRES_IN' => '6 days',
        'PASSPORT_HASH_CLIENT_SECRETS' => false,
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
        if (! $this->app->routesAreCached() && method_exists(Passport::class, 'routes')) {
            // In Passport v11, "routes" method has been removed.
            Passport::routes();
        }

        if (! $this->app->configurationIsCached()) {
            // Override Auth Config
            passportPgtServer()->setPassportAsApiDriver();
        }

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
        $this->app->singleton('passport-pgt-server', function ($app) {
            return new PassportPgtServer($app);
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
            __DIR__.'/../../config/passport-pgt-server.php' => config_path('passport-pgt-server.php'),
        ], 'passport-pgt-server.config');

        // Registering package commands.
        $this->commands($this->commands);
    }
}
