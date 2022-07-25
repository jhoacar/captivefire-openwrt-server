<?php

declare(strict_types=1);

namespace App\GraphQL;

class CurlValidation
{
    /**
     * Endpoint for validation in the host.
     * @var string
     */
    const ROUTE_VALIDATION = '/openwrt';
    /**
     * Method for validation in the host.
     */
    const ROUTE_METHOD = CURLOPT_POST;
    /**
     * Status code for validation in the host.
     */
    const ROUTE_STATUS_CODE = 202;

    /**
     * Check necessary PHP extensions.
     * @return bool
     */
    public static function cURLcheckBasicFunctions(): bool
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
     * @param string $host
     * @param string $token
     * @return bool
     */
    public static function isValidToken($host, $token):bool
    {
        if (!self::cURLcheckBasicFunctions()) {
            return false;
        }

        $host = str_ends_with($host, '/') ? $host : $host . '/';

        $endpoint = $host . self::ROUTE_VALIDATION;
        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $endpoint);
        curl_setopt($curlHandler, self::ROUTE_METHOD, 1);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
        curl_exec($curlHandler);

        $status = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);

        return $status == self::ROUTE_STATUS_CODE;
    }
}
