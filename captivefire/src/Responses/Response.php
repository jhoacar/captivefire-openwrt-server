<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class Response extends BaseResponse
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request = null;

    /**
     * @param string|null $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 200, $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }

    /**
     * Headers for cache control, json response and cors.
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    abstract public function matchRequest($request): bool;

    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    abstract public function handleRequest();
}
