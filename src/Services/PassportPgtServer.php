<?php

namespace Luchavez\PassportPgtServer\Services;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
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
    /**
     * @param  Application  $application
     */
    public function __construct(protected Application $application)
    {
        //
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
     * @param  Closure|null  $load_public_key
     * @param  Closure|null  $load_private_key
     * @return void
     */
    public function setPassportEncryptionKeys(?Closure $load_public_key, ?Closure $load_private_key): void
    {
        if (! $this->application->configurationIsCached()) {
            if ($load_public_key && $value = $load_public_key()) {
                config(['passport.public_key' => $value]);
            }

            if ($load_private_key && $value = $load_private_key()) {
                config(['passport.private_key' => $value]);
            }
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
     * @return Carbon
     */
    public function getTokensExpiresIn(): Carbon
    {
        return Carbon::parse(config('passport-pgt-server.access_token_expires_in'));
    }

    /**
     * @return Carbon
     */
    public function getRefreshTokensExpiresIn(): Carbon
    {
        return Carbon::parse(config('passport-pgt-server.refresh_token_expires_in'));
    }

    /**
     * @return Carbon
     */
    public function getPersonalAccessTokensExpiresIn(): Carbon
    {
        return Carbon::parse(config('passport-pgt-server.personal_access_token_expires_in'));
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
