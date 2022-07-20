<?php

namespace App;

use App\GraphQL\Response as GraphQLResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class used for run all the application.
 */
class Kernel
{
    /**
     * @var string
     */
    public $environment = 'local';
    /**
     * @var bool
     */
    public $debug = false;
    /**
     * @var array
     */
    public $graphql = [
        'uri' => 'graphql',
    ];

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * This function load all the GraphQL response logic and send the response
     * If an error ocurred is catched and the response is used with 500 status code.
     * @param void
     * @return Response
     */
    public function handle()
    {
        try {
            $request = Request::createFromGlobals();
            $provider = null;
            $response = new GraphQLResponse($request, $this->graphql);
            $response->sendGraphQL($provider);
            if ($provider !== null) {
                $this->loadServicesToFile($provider->getServices());
            }
        } catch (\Throwable $throwable) {
            $error = [
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ];
            $response = new Response(json_encode($error), 500, ['Content-Type' => 'application/json']);

            return $response->send();
        }
    }

    /**
     * We load a file named services
     * This file contains all services to restart
     * In background there is a job processing this file
     * and restarting all these services.
     * @param array
     */
    private function loadServicesToFile($services): void
    {
        $servicesContent = '';
        foreach ($services as $service) {
            $servicesContent .= str_replace('\'', '', $service) . PHP_EOL;
        }
        $servicesFile = realpath(__DIR__ . '/../services');
        $fp = fopen($servicesFile, 'a'); //opens file in append mode
        fwrite($fp, $servicesContent);
        fclose($fp);
    }
}
