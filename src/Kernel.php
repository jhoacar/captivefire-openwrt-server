<?php

namespace App;

use App\GraphQL\Response as GraphQLResponse;
use Symfony\Component\HttpFoundation\Request;

class Kernel
{

    public string $environment = 'local';
    public bool $debug = false;
    public array $graphql = [
        'uri' => 'graphql',
    ];

    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function handle()
    {
        try {
            $request = Request::createFromGlobals();
            $response = new GraphQLResponse($request, $this->graphql);
            $response->send();
        } catch (\Throwable $error) {
            $message = "Message: " . $error->getMessage() . "<br>";
            $message .= "Code: " . $error->getCode() . "<br>";
            $message .= "File: " . $error->getFile() . "<br>";
            $message .= "Line: " . $error->getLine() . "<br>";
            $message .= "Trace: " . $error->getTraceAsString() . "<br>";
            echo $message;
        }
    }
}
