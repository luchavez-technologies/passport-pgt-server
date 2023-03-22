<?php

namespace Luchavez\PassportPgtServer\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\PassportPgtServer\DataFactories\UserDataFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

/**
 * Class DefaultAuthController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DefaultAuthController extends Controller
{
    /**
     * Register
     *
     * Register a new user.
     *
     * @group Authentication (Server)
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'email:rfc,dns|unique:users',
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = UserDataFactory::from($validated)->create();

        $data['user'] = $user;

        $log = passportPgtClient()->login($validated['email'], $validated['password']);

        if ($log->getStatusFromResponse() == 200) {
            $data['tokens'] = $log->getDataFromResponse();
        }

        return customResponse()
            ->success()
            ->message('Successfully registered user.')
            ->data($data)
            ->generate();
    }

    /**
     * Logout
     *
     * @group Authentication (Server)
     * @authenticated
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $tokenId = $request->user()->token()->id;

        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        // Revoke an access token...
        $tokenRepository->revokeAccessToken($tokenId);

        // Revoke all of the token's refresh tokens...
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

        return customResponse()
            ->success()
            ->message('Successfully logged out.')
            ->generate();
    }

    /**
     * Get Self
     *
     * @group Authentication (Server)
     * @authenticated
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return customResponse()
            ->data($request->user())
            ->success()
            ->message('Successfully fetched user.')
            ->generate();
    }
}
