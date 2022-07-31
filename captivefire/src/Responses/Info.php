<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\Request;

class Info extends Response
{
    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        if ($this->request === null) {
            $this->request = $request;
        }

        return $this->request->getMethod() === Request::METHOD_GET &&
                $this->request->getPathInfo() === '/info';
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        phpinfo();

        return null;
    }
}
