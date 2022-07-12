<?php

declare(strict_types=1);

namespace App\Tests;

use App\GraphQL\Response;
use App\GraphQL\Schema;
use GraphQL\GraphQL;
use PHPUnit\Framework\TestCase;

final class UhttpdTest extends TestCase
{
    /**
     * @return iterable<array{query: string, field: string}>
     */
    public function queryFieldsDataProvider(): iterable
    {
        yield 'listen_http' => [
            'query' => '{
                uhttpd{
                    listen_http
                }
            }',
            'field' => 'listen_http',
        ];
    }

    /**
     * @dataProvider queryFieldsDataProvider
     */
    public function testCorrectlyExistsField(string $query, string $field)
    {
        self::assertExistField($query, $field);
    }

    /**
     * Helper function to test a query and the expected response.
     *
     * @param array<string, mixed> $expected
     */
    private static function assertExistField(string $query, string $field): void
    {
        $result = GraphQL::executeQuery(Schema::get(), $query)->toArray();

        self::assertTrue(!empty($result['data']), 'An error ocurred in the GraphQL Schema');

        if (empty($result['data'])) {
            return;
        }

        self::assertTrue(!empty($result['data']['uhttpd']), 'uhttpd field is empty or bad implemented');

        if (empty($result['data']['uhttpd'])) {
            return;
        }

        self::assertTrue(!empty($result['data']['uhttpd'][$field]), "$field empty or bad implemented yet");
    }
}
