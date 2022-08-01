<?php

namespace App\Responses;

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
        return $this->setHeaders()->setStatusCode(500)->setContent($content)->send();
    }
}
