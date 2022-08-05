<?php

namespace App\Responses\Update;

use App\Responses\Forbidden;
use App\Responses\Response as BaseResponse;
use App\Utils\Curl;
use App\Validations\CurlValidation;
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
        $this->validation = new CurlValidation();

        if (!$this->isValidatedRequest()) {
            return (new Forbidden())->handleRequest();
        }

        return $this->handleUpdate();

        // $content = (string) json_encode([
        //     'updated' => 'yes',
        // ]);

        // return $this->setStatusCode(200)->setContent($content)->send();
    }

    /**
     * @return $this
     */
    private function handleUpdate()
    {
        $host = $_ENV['CAPTIVEFIRE_ACCESS'];
        $host = str_ends_with($host, '/') ? substr_replace($host, '', -1) : $host;

        $urlToUpdate = $host . '/openwrt/update';

        if ($this->validation === null) {
            return $this;
        }

        $token = $this->validation->getToken($this->request);

        $response = Curl::makeCurl($urlToUpdate, CURLOPT_POST, $token);

        return $this->setStatusCode(200)->setContent((string) $response->data)->send();
    }
}
