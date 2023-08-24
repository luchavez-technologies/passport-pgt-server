<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

use Luchavez\PassportPgtServer\Services\PassportPgtServer;

if (! function_exists('passportPgtServer')) {
    /**
     * @return PassportPgtServer
     */
    function passportPgtServer(): PassportPgtServer
    {
        return resolve('passport-pgt-server');
    }
}

if (! function_exists('passport_pgt_server')) {
    /**
     * @return PassportPgtServer
     */
    function passport_pgt_server(): PassportPgtServer
    {
        return passportPgtServer();
    }
}
