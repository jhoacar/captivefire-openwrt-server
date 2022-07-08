<?php

namespace App\GraphQL;

use App\GraphQL\Mutations\Mutation;
use App\GraphQL\Queries\Query;
use App\Utils\ClassFinder;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as BaseReponse;

class Response extends BaseReponse
{
    public Schema $schema;
    public Request $request;

    public string $uri = "graphql";
    public string $graphql_method = Request::METHOD_POST;

    /**
     * @param Symfony\Component\HttpFoundation\Request
     * @param array $config
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function __construct(Request $request, array $config)
    {
        parent::__construct();
        $this->request = $request;
        foreach ($config as $key => $value)
            $this->$key = $value;
        $this->loadSchema();
    }

    private function getQueries(): ObjectType
    {
        $fields = $this->getFields('App\\GraphQL\\Queries', Query::class, 'getQueries');

        return new ObjectType([
            'name' => 'Query',
            'fields' => $fields
        ]);
    }

    private function getMutations(): ObjectType
    {
        $fields = $this->getFields('App\\GraphQL\\Mutations', Mutation::class, 'getMutations');
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => $fields
        ]);
    }

    /**
     * This function extract all fields in all clasess that implements the interface
     * @param string $namespace
     * @param string $interface
     * @param string $method 
     */
    private function getFields(string $namespace, string $interface, string $method): array
    {
        $fields = [];
        $classes = ClassFinder::getClassesInNamespace($namespace);
        echo json_encode($classes);
        
        foreach ($classes as $class) {
            if (in_array($interface, class_implements($class))) {
                $result = call_user_func(array($class, $method));
                foreach ($result as $key => $value)
                    $fields[$key] = $value;
            }
        }

        // echo json_encode(get_declared_classes());
        die;
        return $fields;
    }

    private function loadSchema(): void
    {
        $this->schema = new Schema([
            'query' => $this->getQueries(),
            'mutation' => $this->getMutations()
        ]);
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
    public function send(): static
    {
        try {

            if ($this->isGraphQLRequest())
                $this->setStatusCode(200)->setContent($this->getContentGraphQL());
            else
                $this->setStatusCode(404)->setContent($this->getNotFound());
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
        $input = $this->request->toArray();
        $query = $input[array_key_first($input)];
        $result = GraphQL::executeQuery($this->schema, $query);
        $output = $result->toArray();
        return json_encode($output);
    }
}
