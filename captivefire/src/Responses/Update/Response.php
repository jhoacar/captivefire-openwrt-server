<?php

namespace App\Responses\Update;

use App\Kernel;
use App\Responses\Response as BaseResponse;
use App\Responses\Unauthorized;
use App\Utils\Curl;
use App\Validations\CurlValidation;
use Symfony\Component\HttpFoundation\Request;

class Response extends BaseResponse
{
    /**
     * Endpoint for update in the host.
     * @var string
     */
    const ROUTE_UPDATE = '/openwrt/update';

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
        if ($this->validation === null) {
            $this->validation = new CurlValidation();
        }

        if (!$this->isValidatedRequest()) {
            return (new Unauthorized())->handleRequest();
        }

        return $this->handleUpdate();
    }

    /**
     * @return $this
     */
    private function handleUpdate()
    {
        $host = (string) getenv(Kernel::CAPTIVEFIRE_ACCESS);
        $host = str_ends_with($host, '/') ? substr_replace($host, '', -1) : $host;

        $urlToUpdate = $host . self::ROUTE_UPDATE;

        if ($this->validation === null) {
            return $this;
        }

        $token = $this->validation->getToken($this->request);

        $response = Curl::makeCurl($urlToUpdate, CURLOPT_POST, $token);

        $pharPath = (string) getenv(Kernel::PATH_PHAR);

        file_put_contents($pharPath, $response->data);

        return $this->setHeaders()
                ->setStatusCode(200)
                ->setContent((string) json_encode(['updated'=>true]))
                ->send();
    }
}
