<?php

namespace App;

include_once 'Response.php';

class Request
{
    private static $host = 'https://api.k2s.cc';
    private static $cookiePath;
    public static $useCookieJar = true;

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
        if (self::$useCookieJar && (null === self::$cookiePath || !file_exists(self::$cookiePath))) {
            self::$cookiePath = '/tmp/scrapper_cookie.txt';
        }

        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.80 Safari/537.36';
        $headers[] = 'origin: '.self::$host;
        $headers[] = 'referer: '.self::$host.'/';
        $headers[] = 'content-type: application/json;charset=UTF-8';
        $headers[] = 'accept: */*';
        $headers[] = 'accept-encoding: gzip, deflate, br';
        $headers[] = ':authority: api.k2s.cc';

        $ch = curl_init(self::$host.$url);
        $responseHeaders = [];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, $isPost);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

        /*
         * If cookie jar ($userCookieJar) is set true only then cookie file will be used
         * else cookie will be stored in array and will be with request
         */
        if (self::$useCookieJar) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, self::$cookiePath);
            curl_setopt($ch, CURLOPT_COOKIEFILE, self::$cookiePath);
        }


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        self::getResponseHeader($ch, $responseHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
        curl_setopt($ch, CURLOPT_HEADER, 1);

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
