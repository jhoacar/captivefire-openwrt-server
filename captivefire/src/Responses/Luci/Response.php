<?php

namespace App\Responses\Luci;

use App\Responses\Forbidden;
use App\Responses\NotFound;
use App\Responses\Response as BaseResponse;
use App\Validations\CurlValidation;
use Symfony\Component\HttpFoundation\Request;
use UciGraphQL\Providers\UciCommandProvider;

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
     * @return int
     */
    private function getLuciPort(): int
    {
        $listen_https = UciCommandProvider::get('uhttpd', 'luci', 'listen_https');
        $option = explode(' ', $listen_https)[0];
        $scheme = explode(':', $option);

        return intval($scheme[count($scheme) - 1]);
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
        $location = $this->request->getSchemeAndHttpHost() . ':' . $this->getLuciPort();
        $content = (string) json_encode([
            'location' => $location,
        ]);

        return $this->setHeaders()
                ->setStatusCode(200)
                ->setContent($content)
                ->send();
    }
}
