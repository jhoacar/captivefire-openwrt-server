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
        return $request->getMethod() === Request::METHOD_GET &&
                $request->getPathInfo() === '/info';
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        $this->setCorsHeaders();
        $this->sendHeaders();

        phpinfo(INFO_ALL ^ INFO_VARIABLES);

        return null;
    }
}
