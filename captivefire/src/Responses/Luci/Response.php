<?php

namespace App\Responses\Luci;

use App\Responses\Response as BaseResponse;
use Symfony\Component\HttpFoundation\Request;
use UciGraphQL\Utils\Command;

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
     * @param
     */
    private function redirectToLuciApp()
    {
        // Command::execute('passwd << EOF
        // Hola
        // Hola
        // EOF');
        $this->headers->set('Location', $this->request->getSchemeAndHttpHost() . ':8443');
        $this->setStatusCode(302)->send();
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
        // if (!$this->isValidatedRequest()) {
        //     return (new Forbidden())->handleRequest();
        // }

        if ($this->request->getMethod() === Request::METHOD_GET) {
            return $this->redirectToLuciApp();
        }

        $content = (string) json_encode([
            'updated' => 'yes',
        ]);

        return $this->setStatusCode(200)->setContent($content)->send();
    }
}
