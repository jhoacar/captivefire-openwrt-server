<?php  declare(strict_types=1);namespace UciGraphQL;use GraphQL\Type\Schema as BaseSchema;use UciGraphQL\Mutations\MutationType;use UciGraphQL\Queries\QueryType;class Schema extends BaseSchema{public static $instance=null;private function __construct($config){parent::__construct($config);}public static function get():self{return self::$instance ===null?(self::$instance=new self(['query' =>QueryType::query(),'mutation' =>MutationType::mutation(),])):self::$instance;}public static function clean():void{QueryType::clean();MutationType::clean();self::$instance=null;}}