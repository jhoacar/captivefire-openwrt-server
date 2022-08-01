<?php

namespace App\Responses;

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
        $content = (string) json_encode([
            'error' => 'You dont have access',
        ]);

        return $this->setHeaders()->setStatusCode(403)->setContent($content)->send();
    }
}
