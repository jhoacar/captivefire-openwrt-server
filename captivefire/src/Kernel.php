<?php

namespace App;

use App\Responses\NotFound;
use App\Responses\Response;
use App\Responses\ServerError;
use Symfony\Component\HttpFoundation\Request;
use UciGraphQL\Utils\ClassFinder;

/**
 * Class used for run all the application.
 */
class Kernel
{
    /**
     * Constructor with configuration.
     */
    public function __construct()
    {
        $_ENV['CAPTIVEFIRE_ACCESS'] = 'http://host.docker.internal:4000';
        $_ENV['APP_GRAPHQL_ROUTE'] = '/graphql';
    }

    /**
     * This function load all the GraphQL response logic and send the response
     * If an error ocurred is catched and the response is used with 500 status code.
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function handle()
    {
        try {
            return $this->handleRequest();
        } catch (\Throwable $throwable) {
            $error = [
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ];

            return (new ServerError())->handleRequest((string) json_encode($error));
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isCorrectClass($class): bool
    {
        $parents = class_parents($class);
        if (!$parents) {
            $parents = [];
        }

        return in_array(Response::class, $parents, true);
    }

    /**
     * @param Response|object $handler
     * @param Request $request
     * @return bool
     */
    private function isMatchedRequest($handler, $request): bool
    {
        return method_exists($handler, 'matchRequest') && $handler->matchRequest($request);
    }

    /**
     * @param Response|object $handler
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    private function getResponseHandled($handler)
    {
        if (!method_exists($handler, 'handleRequest')) {
            return null;
        }

        return $handler->handleRequest();
    }

    /**
     * Return response from global request.
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    private function handleRequest()
    {
        $request = Request::createFromGlobals();

        $classes = ClassFinder::getClassesInNamespace(__DIR__ . '/../', 'App\\Responses');

        foreach ($classes as $class) {
            if ($this->isCorrectClass($class)) {
                $handler = new $class();
                if ($this->isMatchedRequest($handler, $request)) {
                    return $this->getResponseHandled($handler);
                }
            }
        }

        return (new NotFound())->handleRequest();
    }
}
