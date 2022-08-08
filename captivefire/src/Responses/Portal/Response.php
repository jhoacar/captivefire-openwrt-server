<?php

namespace App\Responses\Portal;

use App\Kernel;
use App\Responses\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        $url = (string) file_get_contents((string) getenv(Kernel::PATH_URL_FILE));

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $url = 'https://www.captivefire.net';
        }
        $portalFile = dirname(__DIR__) . '/../Templates/portal.html';
        $content = (string) preg_replace('/\n\r|\n|\r/', '', (string) file_get_contents($portalFile));
        $portal = preg_replace('/{{[\s]*\$url[\s]*}}/', $url, $content);

        return $this->setContent($portal)->send();
    }
}
