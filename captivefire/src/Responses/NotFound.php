<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class NotFound extends Response
{
    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        $content = (string) json_encode([
            'error' => 'Not found',
        ]);
        $this->headers = new ResponseHeaderBag($this->getHeaders());

        $this->setStatusCode(404)->setContent($content);

        return $this->send();
    }
}
