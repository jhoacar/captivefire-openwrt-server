<?php

namespace App\Responses;

class Unauthorized extends Response
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

        return $this->setHeaders()->setStatusCode(401)->setContent($content)->send();
    }
}
