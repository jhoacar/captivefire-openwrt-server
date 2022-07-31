<?php

declare(strict_types=1);

namespace App\Responses\GraphQL;

use App\Responses\Forbidden;
use App\Responses\Response as BaseReponse;
use App\Validations\Validation;
use GraphQL\GraphQL;
use Symfony\Component\HttpFoundation\Request;
use UciGraphQL\Mutations\Uci\UciMutation;
use UciGraphQL\Providers\UciProvider;
use UciGraphQL\Schema;

/**
 * Class used for response all graphql data.
 */
class Response extends BaseReponse
{
    /**
     * @var Request
     */
    public $request;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $curlHost;

    /**
     * @var string
     */
    private $graphql_method = Request::METHOD_POST;

    /**
     * @var Validation|null
     */
    private $validation = null;

    public function __construct()
    {
        $this->uri = $_ENV['APP_GRAPHQL_ROUTE'];
        $this->curlHost = $_ENV['CAPTIVEFIRE_ACCESS'];

        parent::__construct();
    }

    /**
     * @param Validation $validation
     * @return void
     */
    public function setValidation($validation): void
    {
        if ($validation instanceof Validation) {
            $this->validation = $validation;
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isGraphQLRequest($request): bool
    {
        return $this->getBaseUrlGraphQL() === $request->getPathInfo() &&
                $request->getMethod() == $this->graphql_method;
    }

    /**
     * @return string
     */
    private function getBaseUrlGraphQL(): string
    {
        $uri = str_starts_with($this->uri, '/') ? $this->uri : '/' . $this->uri;

        return str_ends_with($uri, '/') ? substr($uri, strlen($uri) - 1) : $uri;
    }

    /**
     * Sends HTTP headers and content.
     * @param UciProvider|null $provider
     * @return $this
     */
    private function sendGraphQL(&$provider)
    {
        try {
            $this->setStatusCode(200)->setContent($this->getContentGraphQL($provider));
        } catch (\Throwable $error) {
            $this->setStatusCode(500)->setContent((string) json_encode(['error' => $error->getMessage()]));
        } finally {
            $this->setJsonHeaders();
            $this->setCorsHeaders();

            return parent::send();
        }
    }

    /**
     * Load Json Response.
     * @return void
     */
    private function setJsonHeaders(): void
    {
        $this->headers->set('Cache-Control', 'no-cache');
        $this->headers->set('Content-Type', 'application/json');
    }

    /**
     * Load the CORS policy.
     * @return void
     */
    private function setCorsHeaders(): void
    {
        /* CORS Policy */
        $this->headers->set('Access-Control-Allow-Origin', '*');
        $this->headers->set('Access-Control-Allow-Methods', '*');
    }

    /**
     * Execute Query for GraphQL.
     * @param UciProvider|null $provider
     * @return string
     */
    private function getContentGraphQL(&$provider = null): string
    {
        // Prepare context that will be available in all field resolvers (as 3rd argument):
        $appContext = new Context();
        $appContext->request = $this->request;

        $input = $this->request->toArray();
        $query = $input[array_key_first($input)];

        $provider = UciMutation::uci()->getProvider();
        $result = GraphQL::executeQuery(
            Schema::get(),
            $query,
            null,
            $appContext,
            (array) $input['variables']
        );
        $output = $result->toArray();

        return (string) json_encode($output);
    }

    /**
     * @inheritdoc
     */
    public function matchRequest($request): bool
    {
        $this->request = $request;

        return $this->isGraphQLRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest()
    {
        if ($this->validation !== null && !$this->validation->isCorrectToken($this->request, $this->curlHost)) {
            return (new Forbidden())->handleRequest();
        }
        $provider = null;
        $result = $this->sendGraphQL($provider);

        if ($provider !== null) {
            $this->loadServicesToFile($provider->getServices());
        }

        return $result;
    }

    /**
     * We load a file named services
     * This file contains all services to restart
     * In background there is a job processing this file
     * and restarting all these services.
     * @param array $services
     * @return void
     */
    private function loadServicesToFile($services): void
    {
        $servicesContent = '';
        foreach ($services as $service) {
            $servicesContent .= str_replace('\'', '', $service) . PHP_EOL;
        }
        $servicesFile = realpath(__DIR__ . '/../') . 'services';
        $fp = fopen((string) $servicesFile, 'a'); //opens file in append mode

        if ($fp !== false) {
            fwrite($fp, $servicesContent);
            fclose($fp);
        }
    }
}
