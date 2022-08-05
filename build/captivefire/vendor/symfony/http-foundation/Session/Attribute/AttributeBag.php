<?php  namespace Symfony\Component\HttpFoundation\Session\Attribute;class AttributeBag implements AttributeBagInterface,\IteratorAggregate,\Countable{private $name='attributes';private $storageKey;protected $attributes=[];public function __construct(string $storageKey='_sf2_attributes'){$this->storageKey=$storageKey;}public function getName(){return $this->name;}public function setName(string $name){$this->name=$name;}public function initialize(array&$attributes){$this->attributes=&$attributes;}public function getStorageKey(){return $this->storageKey;}public function has(string $name){return \array_key_exists($name,$this->attributes);}public function get(string $name,$default=null){return \array_key_exists($name,$this->attributes)?$this->attributes[$name]:$default;}public function set(string $name,$value){$this->attributes[$name]=$value;}public function all(){return $this->attributes;}public function replace(array $attributes){$this->attributes=[];foreach($attributes as $key =>$value){$this->set($key,$value);}}public function remove(string $name){$retval=null;if(\array_key_exists($name,$this->attributes)){$retval=$this->attributes[$name];unset($this->attributes[$name]);}return $retval;}public function clear(){$return=$this->attributes;$this->attributes=[];return $return;}public function getIterator(){return new \ArrayIterator($this->attributes);}public function count(){return \count($this->attributes);}}