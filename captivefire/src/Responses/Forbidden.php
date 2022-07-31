<?php

namespace App\Responses;

class Forbidden extends Response implements HasConstructor
{
    public function __construct()
    {
        $headers = $this->getHeaders();
        $content = (string) json_encode([
            'error' => 'You dont have access',
        ]);

        parent::__construct($content, 403, $headers);
    }

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
        return $this->send();
    }
}
