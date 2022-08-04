<?php

namespace App\Responses\Update;

use App\Responses\Response as BaseResponse;
use Symfony\Component\HttpFoundation\Request;

class Response extends BaseResponse
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
        // if (!$this->isValidatedRequest()) {
        //     return (new Forbidden())->handleRequest();
        // }

        $content = (string) json_encode([
            'updated' => 'yes',
        ]);

        return $this->setStatusCode(200)->setContent($content)->send();
    }
}
