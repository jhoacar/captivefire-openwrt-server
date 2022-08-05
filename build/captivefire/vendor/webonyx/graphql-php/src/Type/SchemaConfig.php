<?php  declare(strict_types=1);namespace GraphQL\Type;use GraphQL\Language\AST\SchemaDefinitionNode;use GraphQL\Language\AST\SchemaTypeExtensionNode;use GraphQL\Type\Definition\Directive;use GraphQL\Type\Definition\ObjectType;use GraphQL\Type\Definition\Type;use GraphQL\Utils\Utils;use function is_callable;class SchemaConfig{public $query;public $mutation;public $subscription;public $types;public $directives;public $typeLoader;public $astNode;public $assumeValid;public $extensionASTNodes;public static function create(array $options=[]){$config=new static();if(!empty($options)){if(isset($options['query'])){$config->setQuery($options['query']);}if(isset($options['mutation'])){$config->setMutation($options['mutation']);}if(isset($options['subscription'])){$config->setSubscription($options['subscription']);}if(isset($options['types'])){$config->setTypes($options['types']);}if(isset($options['directives'])){$config->setDirectives($options['directives']);}if(isset($options['typeLoader'])){Utils::invariant(is_callable($options['typeLoader']),'Schema type loader must be callable if provided but got: %s',Utils::printSafe($options['typeLoader']));$config->setTypeLoader($options['typeLoader']);}if(isset($options['astNode'])){$config->setAstNode($options['astNode']);}if(isset($options['assumeValid'])){$config->setAssumeValid((bool) $options['assumeValid']);}if(isset($options['extensionASTNodes'])){$config->setExtensionASTNodes($options['extensionASTNodes']);}}return $config;}public function getAstNode(){return $this->astNode;}public function setAstNode(SchemaDefinitionNode $astNode){$this->astNode=$astNode;return $this;}public function getQuery(){return $this->query;}public function setQuery($query){$this->query=$query;return $this;}public function getMutation(){return $this->mutation;}public function setMutation($mutation){$this->mutation=$mutation;return $this;}public function getSubscription(){return $this->subscription;}public function setSubscription($subscription){$this->subscription=$subscription;return $this;}public function getTypes(){return $this->types?:[];}public function setTypes($types){$this->types=$types;return $this;}public function getDirectives(){return $this->directives?:[];}public function setDirectives(array $directives){$this->directives=$directives;return $this;}public function getTypeLoader(){return $this->typeLoader;}public function setTypeLoader(callable $typeLoader){$this->typeLoader=$typeLoader;return $this;}public function getAssumeValid(){return $this->assumeValid;}public function setAssumeValid($assumeValid){$this->assumeValid=$assumeValid;return $this;}public function getExtensionASTNodes(){return $this->extensionASTNodes;}public function setExtensionASTNodes(array $extensionASTNodes){$this->extensionASTNodes=$extensionASTNodes;}}