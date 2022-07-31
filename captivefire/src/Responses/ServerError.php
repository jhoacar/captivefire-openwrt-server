<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ServerError extends Response
{
    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        return false;
    }

    /**
     * @param string $content
     * @inheritdoc
     */
    public function handleRequest($content = '')
    {
        $this->headers = new ResponseHeaderBag($this->getHeaders());

        $this->setStatusCode(500)->setContent($content);

        return $this->send();
    }
}
