<?php

declare(strict_types=1);

namespace App\GraphQL;

use Symfony\Component\HttpFoundation\Request;

class Validation
{
    /**
     * @param Request $request
     * @return string
     */
    public static function getAuthorizationHeader($request): string
    {
        return (string) $request->headers->get('Authorization', '');
    }

    /**
     * Return if a request a Authorization header
     *     Authorization: Bearer <token>.
     * @param Request $request
     * @return bool
     */
    public static function isCorrectRequest($request): bool
    {
        return strlen(self::getAuthorizationHeader($request)) > 0 &&
                str_contains(self::getAuthorizationHeader($request), 'Bearer');
    }

    /**
     * Return the token contained in the Authorization header
     *     Authorization: Bearer <token>.
     * @param Request $request
     * @return string
     */
    public static function getToken($request): string
    {
        if (!self::isCorrectRequest($request)) {
            return '';
        }
        $content = explode(' ', self::getAuthorizationHeader($request));

        return count($content) === 2 ? $content[1] : '';
    }

    /**
     * Return true if the token extracted from the header request is correct in the host.
     * @param Request $request
     * @param string $host
     * @return bool
     */
    public static function isCorrectToken($request, $host): bool
    {
        if (!self::isCorrectRequest($request)) {
            return false;
        }

        return CurlValidation::isValidToken($host, self::getToken($request));
    }
}
