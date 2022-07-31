<?php

namespace App\Responses;

class NotFound extends Response implements HasConstructor
{
    public function __construct()
    {
        $headers = $this->getHeaders();
        $content = (string) json_encode([
            'error' => 'Not found',
        ]);

        parent::__construct($content, 404, $headers);
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
