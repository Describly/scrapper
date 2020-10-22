<?php

include_once 'Utils/Auth.php';
include_once 'Utils/Request.php';

use App\Auth;
use App\Request;

class Main
{
    private static $headers = [];
    private static $profileUrl = '/v1/users/me';
    private static $statics = '/v1/users/me/statistic';
    private static $username;
    private static $password;
    private static $cookie;

    /**
     * Main constructor make the required call to get the access_token
     * and list of cookie required to make the other curl calls
     */
    public function __construct()
    {
        if (isset($_ENV['DEBUG'])) {
            Request::$debug = $_ENV['DEBUG'];
        }
    }

    /**
     * This method can be called to login the user in the system
     */
    private function attemptLogin()
    {
        self::$cookie = Auth::init(self::$username, self::$password);
        if (!Request::$useCookieJar) {
            self::$headers[] = 'Cookie: '.implode('; ', self::$cookie);
        }
    }


    /**
     * This method can be called to initialize the scrapper
     *
     * @param null $username
     * @param null $password
     *
     * @return array
     */
    public function init($username = null, $password = null)
    {
        /*
         * Setting the static variable username & password
         * so that login can be attempted from anywhere in the class without passing it.
         */
        self::$username = $username;
        self::$password = $password;

        $this->attemptLogin();

        $data = [];
        $userInfo = $this->getLoggedInUserInfo();
        if (array_key_exists('accountType', $userInfo)) {
            $data['accountType'] = $userInfo['accountType'];
        }

        $profile = $this->getStats();
        if (array_key_exists('dailyTraffic', $profile)) {
            if (array_key_exists('total', $profile['dailyTraffic'])) {
                $data['Traffic left today for viewing/downloading'] = $profile['dailyTraffic']['total'];
            }
            if (array_key_exists('used', $profile['dailyTraffic'])) {
                $data['Used traffic today'] = $profile['dailyTraffic']['used'];
            }
        }
        $data['Cookies'] = self::$cookie;
        return $data;
    }


    /**
     * This method returns the profile stats
     *
     * @return mixed
     */
    private function getStats()
    {
        $response = Request::get(self::$statics, self::$headers);
        if ($response->getStatusCode() !== 200) {
            $this->attemptLogin();
            $response = Request::get(self::$statics, self::$headers);
        }
        return json_decode($response->getResponse(), true);
    }


    /**
     * This methods returns the account type of the user
     *
     * @return mixed
     */
    private function getLoggedInUserInfo()
    {
        $response = Request::get(self::$profileUrl, self::$headers);
        if ($response->getStatusCode() !== 200) {
            $this->attemptLogin();
            $response = Request::get(self::$profileUrl, self::$headers);
        }
        return json_decode($response->getResponse(), true);
    }
}

// Set the username password here
$username = null;
$password = null;

// You can pass username and password using the docker env
if (isset($_ENV['USERNAME'])) {
    $username = $_ENV['USERNAME'];
}

if (isset($_ENV['PASSWORD'])) {
    $password = $_ENV['PASSWORD'];
}


$app = new Main();
$data = $app->init($username, $password);

foreach ($data as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            echo $key.'['.$k.'] => '.$v;
            echo "\n";
        }
    } else {
        echo "$key => $value \n";
    }
}
