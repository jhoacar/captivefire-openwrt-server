<?php

namespace App\Utils;

class Curl
{
    /**
     * Check necessary PHP extensions.
     * @return bool
     */
    private static function cURLcheckBasicFunctions(): bool
    {
        if (!function_exists('curl_init') &&
      !function_exists('curl_setopt') &&
      !function_exists('curl_exec') &&
      !function_exists('curl_close')) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return the result of a curl with the Authorization header.
     * @param string $url
     * @param int $method
     * @param string $token
     * @return CurlResponse
     */
    public static function makeCurl($url, $method, $token): CurlResponse
    {
        $response = new CurlResponse();

        if (!self::cURLcheckBasicFunctions()) {
            return $response;
        }

        $curlHandler = curl_init();

        if ($curlHandler !== false) {
            curl_setopt($curlHandler, CURLOPT_URL, $url);
            curl_setopt($curlHandler, $method, 1);
            curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandler, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
            $response->data = curl_exec($curlHandler);
            $response->status = (int) curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
            curl_close($curlHandler);
        }

        return $response;
    }
}
