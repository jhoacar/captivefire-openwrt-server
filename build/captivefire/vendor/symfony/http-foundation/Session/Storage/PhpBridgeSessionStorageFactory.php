<?php  namespace Symfony\Component\HttpFoundation\Session\Storage;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Session\Storage\Proxy\AbstractProxy;class_exists(PhpBridgeSessionStorage::class);class PhpBridgeSessionStorageFactory implements SessionStorageFactoryInterface{private AbstractProxy|\SessionHandlerInterface|null $handler;private?MetadataBag $metaBag;private bool $secure;public function __construct(AbstractProxy|\SessionHandlerInterface $handler=null,MetadataBag $metaBag=null,bool $secure=false){$this->handler=$handler;$this->metaBag=$metaBag;$this->secure=$secure;}public function createStorage(?Request $request):SessionStorageInterface{$storage=new PhpBridgeSessionStorage($this->handler,$this->metaBag);if($this->secure &&$request?->isSecure()){$storage->setOptions(['cookie_secure' =>true]);}return $storage;}}