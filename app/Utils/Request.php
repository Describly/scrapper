<?php

namespace App;

include_once 'Response.php';

class Request
{
    private static $host = 'https://api.k2s.cc';

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
        $ch = curl_init();
        $responseHeaders = [];
        curl_setopt($ch, CURLOPT_URL, self::$host.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, $isPost);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_VERBOSE, $_ENV['DEBUG']);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        self::getResponseHeader($ch, $responseHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close ($ch);

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
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );
    }
}
