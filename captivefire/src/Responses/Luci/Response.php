<?php

namespace App\Responses\Luci;

use App\Responses\NotFound;
use App\Responses\Response as BaseResponse;
use App\Responses\Unauthorized;
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
        // if ($this->validation === null) {
        //     $this->validation = new CurlValidation();
        // }

        // if (!$this->isValidatedRequest()) {
        //     return (new Unauthorized())->handleRequest();
        // }

        return $this->handleLuciResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function handleLuciResponse()
    {
        if ($this->request === null) {
            return (new NotFound())->handleRequest();
        }

        $luciUri = '/cgi-bin/luci';
        $location = $this->request->getSchemeAndHttpHost() . $this->request->getBaseUrl() . $luciUri;
        $this->headers->set('Location', $location);

        return $this->setStatusCode(302)
                ->send();
    }
}
