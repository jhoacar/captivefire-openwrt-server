<?php

namespace App\GraphQL;

use App\GraphQL\Queries\Uci\UciType;
use App\GraphQL\Schema;
use App\Utils\Command;
use App\Utils\UciCommand;
use GraphQL\GraphQL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as BaseReponse;

class Response extends BaseReponse
{
    /**
     * @var Request
     */
    public $request;
    /**
     * @var string
     */
    public $uri = "graphql";
    /**
     * @var string
     */
    public $graphql_method = Request::METHOD_POST;

    /**
     * @param Symfony\Component\HttpFoundation\Request
     * @param array $config
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function __construct(Request $request, array $config)
    {
        parent::__construct();
        $this->request = $request;
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function isGraphQLRequest(): bool
    {
        return $this->request->getMethod() == $this->graphql_method &&
            $this->request->getPathInfo() === $this->getBaseUrlGraphQL();
    }

    public function getBaseUrlGraphQl(): string
    {
        return '/' . $this->uri;
    }

    public function getNotFound(): string
    {
        return json_encode([
            'error' => 'Not found in server'
        ]);
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return $this
     */
    public function send()
    {
        try {
            if ($this->isGraphQLRequest()) {
                $this->setStatusCode(200)->setContent($this->getContentGraphQL());
            } else {
                $this->setStatusCode(404)->setContent($this->getNotFound());
            }
        } catch (\Throwable $error) {
            $this->setContent(json_encode(['error' => $error->getMessage()]));
        } finally {
            $this->headers->set('Content-Type', 'application/json');
            return parent::send();
        }
    }

    /**
     * Execute Query for GraphQL
     * @return string
     */
    public function getContentGraphQL(): string
    {
        // Prepare context that will be available in all field resolvers (as 3rd argument):
        $appContext = new Context();
        $appContext->request = $this->request;

        $input = $this->request->toArray();
        $query = $input[array_key_first($input)];
        $result = GraphQL::executeQuery(
            Schema::get(),
            $query,
            null,
            $appContext,
            (array) $input['variables']
        );
        $output = $result->toArray();
        $output = UciCommand::getUciConfiguration();
        // $output = explode(PHP_EOL, Command::execute("uci show"));

        return json_encode($output);
    }
}
