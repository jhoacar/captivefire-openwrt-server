<?php

declare(strict_types=1);

namespace App\Validations;

use App\Utils\Curl;

class CurlValidation extends Validation
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
    public function cURLcheckBasicFunctions(): bool
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
    public function isValidToken($host, $token):bool
    {
        if (!$this->cURLcheckBasicFunctions()) {
            return false;
        }
        $host = str_ends_with($host, '/') ? substr_replace($host, '', -1) : $host;

        $endpoint = $host . self::ROUTE_VALIDATION;

        return Curl::makeCurl($endpoint, self::ROUTE_METHOD, $token)->status === self::ROUTE_STATUS_CODE;
    }

    /**
     * @inheritdoc
     */
    public function isValidatedRequest($request): bool
    {
        if (!$this->isCorrectRequest($request)) {
            return false;
        }

        $host = $_ENV['CAPTIVEFIRE_ACCESS'];

        return $this->isValidToken($host, $this->getToken($request));
    }
}
