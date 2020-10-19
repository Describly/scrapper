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
     */
    public static function init($username, $password)
    {
        // Since grant_type, client_id, & client_secret is not changing so hard-coding it
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => 'k2s_web_app',
            'client_secret' => 'pjc8pyZv7vhscexepFNzmu4P',
            'username' => $username,
            'password' => $password
        ];
        $response = Request::post(self::$tokenUrl, $params);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Unable to fetch the access token from the server.');
        }

        $responseArr = json_decode($response->getResponse(), true);

        // Set and return the header required for next request
        $reqHeaders = [];

        if (array_key_exists('access_token', $responseArr) && array_key_exists('token_type', $responseArr)) {
//            $reqHeaders['Authorization'] = $responseArr['token_type'].' '.$responseArr['access_token'];
        }

        $cookies = $response->getCookies();
        $requiredCookieName = ['__cfduid', 'pcId', 'accessToken', 'refreshToken'];
        $requiredCookies = [];
        if (!empty($cookies)) {
            // Filtering only required cookie for next request
            foreach ($cookies as $cookie) {
                $cookieArr = explode('=', $cookie);
                if (count($cookieArr) <= 0) {
                    continue;
                }
                if (in_array($cookieArr[0], $requiredCookieName, false)) {
                    $requiredCookies[] = $cookie;
                }
            }
            $reqHeaders['cookie'] = implode('; ', $requiredCookies);
        }
        return $reqHeaders;
    }
}
