<?php

namespace App;

include_once 'Request.php';

use App\Request;

class Auth
{
    private static $tokenUrl = '/v1/auth/token';

    /**
     * This method makes a curl request for authentication
     *
     * @param $username
     * @param $password
     *
     * @return array
     */
    public static function init($username, $password)
    {
        // Since grant_type, client_id, & client_secret is not changing so hard-coding it
        $params = [
            'grant_type' => 'password',
            'client_id' => 'k2s_web_app',
            'client_secret' => 'pjc8pyZv7vhscexepFNzmu4P',
            'username' => $username,
            'password' => $password
        ];

        // Setting the login credentials
        $params['username'] = $username;
        $params['password'] = $password;
        $params['grant_type'] = 'password';
        $response = Request::post(self::$tokenUrl, $params);
        if ($response->getStatusCode() !== 200) {
//            throw new \RuntimeException('Unable to fetch the access token from the server.');
        }

        // Set and return the header required for next request if cookiejar is not set
        $cookies = [];
        if (!Request::$useCookieJar) {
            $cookies[] = 'Cookie: '.implode('; ', $response->getCookies());
        } else {
            $cookies = $response->getCookies();
        }
        return $cookies;
    }
}
