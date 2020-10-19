<?php

include_once 'Utils/Auth.php';
include_once 'Utils/Request.php';

use App\Auth;
use App\Request;

class Main
{
    private static $headers;

    /**
     * Main constructor make the required call to get the access_token
     * and list of cookie required to make the other curl calls
     */
    public function __construct()
    {
        self::$headers = Auth::init($_ENV['USERNAME'], $_ENV['PASSWORD']);
    }

    public function scrap()
    {
        $data = [];
        $userInfo = $this->getLoggedInUserInfo();
        print_r(self::$headers);
        die();
        return 'Hellow';
    }


    private function getLoggedInUserInfo()
    {
        $response = Request::get('/v1/users/me', self::$headers);

        print_r($response->getResponse());
        print_r($response->getHeaders());
        print_r($response->getStatusCode());
    }
}


$app = new Main();
echo $app->scrap();
