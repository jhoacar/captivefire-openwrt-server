<?php  declare(strict_types=1);namespace GraphQL\Type\Definition;use Exception;use GraphQL\Error\InvariantViolation;use GraphQL\Language\AST\ObjectTypeDefinitionNode;use GraphQL\Language\AST\ObjectTypeExtensionNode;use GraphQL\Utils\Utils;use function call_user_func;use function is_array;use function is_callable;use function is_string;use function sprintf;class ObjectType extends Type implements OutputType,CompositeType,NullableType,NamedType{public $astNode;public $extensionASTNodes;public $resolveFieldFn;private $fields;private $interfaces;private $interfaceMap;public function __construct(array $config){if(!isset($config['name'])){$config['name']=$this->tryInferName();}Utils::invariant(is_string($config['name']),'Must provide name.');$this->name=$config['name'];$this->description=$config['description']??null;$this->resolveFieldFn=$config['resolveField']??null;$this->astNode=$config['astNode']??null;$this->extensionASTNodes=$config['extensionASTNodes']??[];$this->config=$config;}public static function assertObjectType($type){Utils::invariant($type instanceof self,'Expected '.Utils::printSafe($type).' to be a GraphQL Object type.');return $type;}public function getField($name){if($this->fields ===null){$this->getFields();}Utils::invariant(isset($this->fields[$name]),'Field "%s" is not defined for type "%s"',$name,$this->name);return $this->fields[$name];}public function hasField($name){if($this->fields ===null){$this->getFields();}return isset($this->fields[$name]);}public function getFields(){if($this->fields ===null){$fields=$this->config['fields']??[];$this->fields=FieldDefinition::defineFieldMap($this,$fields);}return $this->fields;}public function implementsInterface($iface){$map=$this->getInterfaceMap();return isset($map[$iface->name]);}private function getInterfaceMap(){if(!$this->interfaceMap){$this->interfaceMap=[];foreach($this->getInterfaces()as $interface){$this->interfaceMap[$interface->name]=$interface;}}return $this->interfaceMap;}public function getInterfaces(){if($this->interfaces ===null){$interfaces=$this->config['interfaces']??[];$interfaces=is_callable($interfaces)?call_user_func($interfaces):$interfaces;if($interfaces !==null &&!is_array($interfaces)){throw new InvariantViolation(sprintf('%s interfaces must be an Array or a callable which returns an Array.',$this->name));}$this->interfaces=$interfaces?:[];}return $this->interfaces;}public function isTypeOf($value,$context,ResolveInfo $info){return isset($this->config['isTypeOf'])?call_user_func($this->config['isTypeOf'],$value,$context,$info):null;}public function assertValid(){parent::assertValid();Utils::invariant($this->description ===null ||is_string($this->description),sprintf('%s description must be string if set, but it is: %s',$this->name,Utils::printSafe($this->description)));$isTypeOf=$this->config['isTypeOf']??null;Utils::invariant($isTypeOf ===null ||is_callable($isTypeOf),sprintf('%s must provide "isTypeOf" as a function, but got: %s',$this->name,Utils::printSafe($isTypeOf)));foreach($this->getFields()as $field){$field->assertValid($this);}}}