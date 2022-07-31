<?php

namespace App\Responses;

class ServerError extends Response implements HasConstructor
{
    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $headers = $this->getHeaders();
        parent::__construct($content, 500, $headers);
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
