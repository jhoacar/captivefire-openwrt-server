<?php

namespace App\Responses;

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

        return $this->setHeaders()->setStatusCode(404)->setContent($content)->send();
    }
}
