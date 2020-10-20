<?php

namespace App;

include_once 'Response.php';

class Request
{
    private static $host = 'https://api.k2s.cc';
    private static $cookiePath;
    public static $useCookieJar = true; // Define we store cookie in file or in array
    public static $debug = false; // Can be used to set the curl verbose

    /**
     * This method can be used to send get request
     *
     * @param $url
     * @param array $headers
     *
     * @return bool|string
     */
    public static function get($url, $headers = [])
    {
        return self::initCurl($url, $headers);
    }


    /**
     * This method can be used to send post request
     *
     * @param $url
     * @param $params
     * @param array $headers
     *
     * @return Response
     */
    public static function post($url, $params, $headers = [])
    {
        return self::initCurl($url, $headers, $params, true);
    }


    /**
     * This method initialize the curl request and sends the request based on the parameter given
     *
     * @param $url
     * @param array $headers
     * @param array $params
     * @param false $isPost
     *
     * @return Response
     */
    private static function initCurl($url, $headers = [], $params = [], $isPost = false)
    {
        if (self::$useCookieJar && !file_exists(self::$cookiePath)) {
            self::$cookiePath = '/tmp/scrapper_cookie.txt';
        }

        $userAgent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
        $headers[] = 'content-type: application/json;charset=UTF-8';
        $headers[] = 'accept: application/json';

        $ch = curl_init(self::$host.$url);
        $responseHeaders = [];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, self::$debug);

        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, $isPost);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

        /*
         * If cookiejar (variable: $userCookieJar) is set to true only then cookie file will be used
         * else cookie will be stored in array and will be with request
         */
        if (self::$useCookieJar) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, self::$cookiePath);
            curl_setopt($ch, CURLOPT_COOKIEFILE, self::$cookiePath);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (self::$useCookieJar) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }

        self::getResponseHeader($ch, $responseHeaders);

        ob_start();
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        ob_end_clean();

        curl_close ($ch);
        return new Response($response, $responseHeaders, $httpCode);
    }


    /**
     * This method returns the headers of response
     *
     * @param $ch
     * @param $responseHeaders
     */
    private static function getResponseHeader(&$ch, &$responseHeaders)
    {
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // this function is called by curl for each header received
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function($curl, $header) use (&$responseHeaders)
            {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) { // ignore invalid headers
                    return $len;
                }

                $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );
    }
}
