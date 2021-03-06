<?php

namespace Psc\CMS\Controller;

use Psc\Code\Code;
use Psc\Code\Generate\GClass;
use Psc\CMS\Roles\ControllerDependenciesProvider;
use Psc\CMS\ContainerAware;

/**
 * 
 * 1. needs to resvole names to controller FQNs (check)
 *    (needs the Dependency namespace for this to simplify, but the webforge container could create such a factory for example)
 * 
 * 2. needs to inject dependencies into the constructor (or through interfaces) to the controller
 *    How do we provide a flexible way to inject everything we need in project => (Simple-)Container aka ProjectContainer?
 * 
 * 3. needs to be called from INSIDE other controllers to help with cross referencings shit
 *    => needs to be in the container?
 * 
 * 
 */
class Factory {

  /**
   * Mappings from $names to ControllerFQNs
   * 
   * @var GClass[]
   */
  protected $classes = array();

  /**
   * @var array
   */
  protected $controllers = array();

  /**
   * @var string
   */
  protected $defaultNamespace;


  protected $dependencies;

  public function __construct($defaultNamespace, ControllerDependenciesProvider $dependencies) {
    $this->defaultNamespace = $defaultNamespace;
    $this->dependencies = $dependencies;
  }

  /**
   *  @return Controller-Instance
   */
  public function getController($controllerName) {
    $controllerClass = $this->isFQN($controllerName) ? new GClass($controllerName) : $this->getControllerGClass($controllerName);

    $args = array();
    $containerInjected = FALSE;
    if ($this->isInstanceOf($controllerClass, 'Psc\CMS\Controller\ContainerController')) {
      $args = array(
        $this->dependencies->getDoctrinePackage(),
        $this->dependencies->getContainer()
      );
      $containerInjected = TRUE;
      
    } elseif ($this->isInstanceOf($controllerClass, 'Psc\CMS\Controller\SimpleContainerController')) {
      $args = array(
        $this->dependencies->getTranslationContainer(),
        $this->dependencies->getDoctrinePackage(),
        NULL,
        NULL,
        NULL,
        $this->dependencies->getSimpleContainer()
      );
      $containerInjected = TRUE;

    } elseif ($this->isInstanceOf($controllerClass, 'Psc\CMS\Controller\AbstractEntityController')) {
      $args[] = $this->dependencies->getTranslationContainer();
      $args[] = $this->dependencies->getDoctrinePackage();
    }

    $controller = $controllerClass->newInstance($args);

    if ($controller instanceof LanguageAware) {
      $container = $this->dependencies->getSimpleContainer();

      $controller->setLanguages($container->getLanguages());
      $controller->setLanguage($container->getLanguage());
    }

    if (!$containerInjected && $controller instanceof ContainerAware) {
      $controller->setContainer($this->dependencies->getContainer());
    }

    return $controller;
  }

  /**
   * @return GClass
   */
  protected function getControllerGClass($controllerName) {
    if (isset($this->classes[$controllerName])) {
      return $this->classes[$controllerName];
    }

    $gClass = new GClass();
    $gClass->setClassName($controllerName.'Controller');
    $gClass->setNamespace($this->getDefaultNamespace());

    return $gClass;
  }

  /**
   * @return bool
   */
  protected function isFQN($fqn) {
    return mb_strpos($fqn, '\\') !== FALSE;
  }

  /**
   * Returns the default Namespace where to search for controllers
   * 
   * without \ before and after the namespace
   */
  public function getDefaultNamespace() {
    return $this->defaultNamespace;
  }

  /**
   * @chainable
   */
  public function setDefaultNamespace($fqn) {
    $this->defaultNamespace = $fqn;
    return $this;
  }

  /**
   * @chainable
   */
  public function setControllerFQN($controllerName, $controllerFQN) {
    $this->classes[$controllerName] = new GClass($controllerFQN);
    return $this;
  }

  /**
   * @return bool
   */
  protected function isInstanceOf(GClass $controllerClass, $fqn) {
    return $controllerClass->getReflection()->isSubclassOf($fqn);
  }
}
