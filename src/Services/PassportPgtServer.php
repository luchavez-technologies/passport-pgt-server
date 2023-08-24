<?php

namespace Luchavez\PassportPgtServer\Services;

use Closure;
use Illuminate\Foundation\Application;
use Luchavez\PassportPgtClient\Traits\HasAuthMethodsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use RuntimeException;

/**
 * Class PassportPgtServer
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class PassportPgtServer
{
    use HasAuthMethodsTrait;

    /**
     * @var array
     */
    protected array $controllers = [];

    /**
     * @param Application $application
     */
    public function __construct(protected Application $application)
    {
        // Rehydrate first
        $this->controllers = $this->getControllers()->toArray();

        $this->setAuthController(config('passport-pgt-server.auth_controller'), false, false);
    }

    /**
     * @return string
     */
    public function getMainTag(): string
    {
        return 'passport-pgt-server';
    }

    /***** CONFIG-RELATED *****/

    /**
     * @link https://laravel.com/docs/8.x/passport#installation
     *
     * @return void
     */
    public function setPassportAsApiDriver(): void
    {
        $key = 'auth.guards.api';

        $value = [
            'driver' => 'passport',
            'provider' => 'users',
        ];

        $apiAuthConfig = config($key);

        if ($apiAuthConfig && isset($apiAuthConfig['driver'])) {
            if ($apiAuthConfig['driver'] !== $value['driver']) {
                throw new RuntimeException('Failed to set passport as api driver. Another driver already set.');
            }
        } else {
            config([$key => $value]);
        }
    }

    /**
     * @param Closure|null $load_public_key
     * @param Closure|null $load_private_key
     * @return void
     */
    public function setPassportEncryptionKeys(Closure|null $load_public_key, Closure|null $load_private_key): void
    {
        if (! $this->application->configurationIsCached()) {
            if ($load_public_key && $value=$load_public_key()) {
                config(['passport.public_key' => $value]);
            }

            if ($load_private_key && $value=$load_private_key()) {
                config(['passport.private_key' => $value]);
            }
        }
    }

    /***** CONTROLLER-RELATED *****/

    /**
     * @param  string  $controller
     * @param  bool  $override
     * @param  bool  $throw_error
     */
    public function setAuthController(string $controller, bool $override = false, bool $throw_error = true): void
    {
        if (is_subclass_of($controller, Controller::class)) {
            $this->setRegisterController($controller, $override, $throw_error);
            $this->setLogoutController($controller, $override, $throw_error);
            $this->setMeController($controller, $override, $throw_error);
        }
    }

    /***** TOKEN EXPIRATIONS *****/

    /**
     * @return bool
     */
    public function hashClientSecrets(): bool
    {
        return config('passport-pgt-server.hash_client_secrets');
    }

    /**
     * @return string
     */
    public function getTokensExpiresInUnit(): string
    {
        return config('passport-pgt-server.access_token_expires_in.time_unit');
    }

    /**
     * @return int
     */
    public function getTokensExpiresInValue(): int
    {
        return config('passport-pgt-server.access_token_expires_in.time_value');
    }

    /**
     * @return Carbon
     */
    public function getTokensExpiresIn(): Carbon
    {
        return now()->add($this->getTokensExpiresInValue().' '.$this->getTokensExpiresInUnit());
    }

    /**
     * @return string
     */
    public function getRefreshTokensExpiresInUnit(): string
    {
        return config('passport-pgt-server.access_token_expires_in.time_unit');
    }

    /**
     * @return int
     */
    public function getRefreshTokensExpiresInValue(): int
    {
        return config('passport-pgt-server.access_token_expires_in.time_value');
    }

    /**
     * @return Carbon
     */
    public function getRefreshTokensExpiresIn(): Carbon
    {
        return now()->add($this->getRefreshTokensExpiresInValue().' '.$this->getRefreshTokensExpiresInUnit());
    }

    /**
     * @return string
     */
    public function getPersonalAccessTokensExpiresInUnit(): string
    {
        return config('passport-pgt-server.access_token_expires_in.time_unit');
    }

    /**
     * @return int
     */
    public function getPersonalAccessTokensExpiresInValue(): int
    {
        return config('passport-pgt-server.access_token_expires_in.time_value');
    }

    /**
     * @return Carbon
     */
    public function getPersonalAccessTokensExpiresIn(): Carbon
    {
        return now()->add($this->getPersonalAccessTokensExpiresInValue().' '.$this->getPersonalAccessTokensExpiresInUnit());
    }

    /***** MODELS & BUILDERS *****/

    /**
     * @return string
     */
    public function getTokenModel(): string
    {
        return Passport::tokenModel();
    }

    /**
     * @return Builder
     */
    public function getTokenBuilder(): Builder
    {
        return Passport::tokenModel()::query();
    }

    /**
     * @return string
     */
    public function getRefreshTokenModel(): string
    {
        return Passport::refreshTokenModel();
    }

    /**
     * @return Builder
     */
    public function getRefreshTokenBuilder(): Builder
    {
        return Passport::refreshTokenModel()::query();
    }

    /**
     * @return string
     */
    public function getPersonalAccessTokenModel(): string
    {
        return Passport::personalAccessClientModel();
    }

    /**
     * @return Builder
     */
    public function getPersonalAccessTokenBuilder(): Builder
    {
        return Passport::personalAccessClientModel()::query();
    }

    /**
     * @return string
     */
    public function getClientModel(): string
    {
        return Passport::clientModel();
    }

    /**
     * @return Builder
     */
    public function getClientBuilder(): Builder
    {
        return Passport::clientModel()::query();
    }
}
