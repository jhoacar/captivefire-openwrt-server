<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Forbidden extends Response
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
        $this->headers = new ResponseHeaderBag($this->getHeaders());
        $content = (string) json_encode([
            'error' => 'You dont have access',
        ]);
        $this->setStatusCode(403)->setContent($content);

        return $this->send();
    }
}
