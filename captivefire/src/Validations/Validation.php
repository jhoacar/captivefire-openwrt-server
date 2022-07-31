<?php

declare(strict_types=1);

namespace App\Validations;

use Symfony\Component\HttpFoundation\Request;

abstract class Validation
{
    /**
     * @param Request $request
     * @return string
     */
    public function getAuthorizationHeader($request): string
    {
        return (string) $request->headers->get('Authorization', '');
    }

    /**
     * Return if a request a Authorization header
     *     Authorization: Bearer <token>.
     * @param Request $request
     * @return bool
     */
    public function isCorrectRequest($request): bool
    {
        return strlen($this->getAuthorizationHeader($request)) > 0 &&
                str_contains($this->getAuthorizationHeader($request), 'Bearer');
    }

    /**
     * Return the token contained in the Authorization header
     *     Authorization: Bearer <token>.
     * @param Request $request
     * @return string
     */
    public function getToken($request): string
    {
        if (!$this->isCorrectRequest($request)) {
            return '';
        }
        $content = explode(' ', $this->getAuthorizationHeader($request));

        return count($content) === 2 ? $content[1] : '';
    }

    /**
     * Return true if the token extracted from the header request is correct in the host.
     * @param Request $request
     * @param string $host
     * @return bool
     */
    abstract public function isCorrectToken($request, $host): bool;
}
