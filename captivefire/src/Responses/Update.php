<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\Request;

class Update extends Response
{
    /**
     * @param Request $request
     * @return bool
     */
    private function isUpdateRequest($request): bool
    {
        return $request->getMethod() === Request::METHOD_POST &&
                $request->getPathInfo() === '/update';
    }

    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        return $this->isUpdateRequest($this->request = $request);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        if (!$this->isValidatedRequest()) {
            return (new Forbidden())->handleRequest();
        }

        return null;
    }
}
