<?php

namespace App\Responses;

use App\Validations\Validation;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class Response extends BaseResponse
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request = null;

    /**
     * @var Validation|null
     */
    protected $validation = null;

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
     * Load Json Response.
     * @return void
     */
    private function setJsonHeaders(): void
    {
        $this->headers->set('Cache-Control', 'no-cache');
        $this->headers->set('Content-Type', 'application/json');
    }

    /**
     * Load the CORS policy.
     * @return void
     */
    private function setCorsHeaders(): void
    {
        /* CORS Policy */
        $this->headers->set('Access-Control-Allow-Origin', '*');
        $this->headers->set('Access-Control-Allow-Methods', '*');
    }

    /**
     * Headers for cache control, json response and cors.
     * @return $this
     */
    public function setHeaders()
    {
        $this->setJsonHeaders();
        $this->setCorsHeaders();

        return $this;
    }

    /**
     * Manage validation with the abstract class validation.
     * @return bool
     */
    public function isValidatedRequest()
    {
        return $this->validation !== null && $this->validation->isValidatedRequest($this->request);
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
