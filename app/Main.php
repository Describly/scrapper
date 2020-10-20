<?php

include_once 'Utils/Auth.php';
include_once 'Utils/Request.php';

use App\Auth;
use App\Request;

class Main
{
    private static $headers = [];

    /**
     * Main constructor make the required call to get the access_token
     * and list of cookie required to make the other curl calls
     */
    public function __construct()
    {
        $cookies = Auth::init('describly2@gmail.com', 'K2s@12345');
        if (!Request::$useCookieJar) {
            self::$headers = array_merge(self::$headers, $cookies);
        }
    }

    public function scrap()
    {
        $data = [];
        $userInfo = $this->getLoggedInUserInfo();
        print_r(self::$headers);
        die();
        return 'Hello';
    }


    private function getLoggedInUserInfo()
    {
        $response = Request::get('/v1/users/me');
        print_r('****************');
        print_r($response->getStatusCode());
        die();
    }
}


$app = new Main();
echo $app->scrap();
