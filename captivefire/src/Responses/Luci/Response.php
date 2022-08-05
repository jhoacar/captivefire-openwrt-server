<?php

namespace App\Responses\Luci;

use App\Responses\Forbidden;
use App\Responses\Response as BaseResponse;
use App\Validations\CurlValidation;
use Symfony\Component\HttpFoundation\Request;

class Response extends BaseResponse
{
    /**
     * @param Request $request
     * @return bool
     */
    private function isLuciRequest($request): bool
    {
        return $request->getPathInfo() === '/luci';
    }

    /**
     * @return $this
     */
    private function redirectToLuciApp()
    {
        if ($this->request !== null) {
            $this->headers->set('Location', $this->request->getSchemeAndHttpHost() . ':8443');
        }

        return $this->setStatusCode(302)->send();
    }

    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        return $this->isLuciRequest($this->request = $request);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        if ($this->validation === null) {
            $this->validation = new CurlValidation();
        }
       
        if (!$this->isValidatedRequest()) {
            return (new Forbidden())->handleRequest();
        }

        if ($this->request !== null && $this->request->getMethod() === Request::METHOD_GET) {
            return $this->redirectToLuciApp();
        }

        $content = (string) json_encode([
            'updated' => 'yes',
        ]);

        return $this->setStatusCode(200)->setContent($content)->send();
    }
}
