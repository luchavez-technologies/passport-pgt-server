<?php

namespace Luchavez\PassportPgtServer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class PassportPgtServer
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\PassportPgtServer\Services\PassportPgtServer
 */
class PassportPgtServer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'passport-pgt-server';
    }
}
