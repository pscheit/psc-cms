<?php

namespace Psc\CMS\Roles;

class AbstractContainerTest extends \Psc\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Psc\\CMS\\Roles\\AbstractContainer';
    parent::setUp();

    $this->dc = $this->doublesManager->createDoctrinePackageMock();
    $this->languages = array('de', 'en');
    $this->language = 'de';

    $this->container = new \Psc\Test\CMS\Container('Psc\Test\Controllers', $this->dc, $this->languages, $this->language);
  }

  public function testPre() {
    $this->assertInstanceOf($this->chainClass, $this->container);
  }

  public function testReturnsTheCurrentPackage() {
    $this->assertInstanceOf('Webforge\Framework\Package\Package', $this->container->getPackage());
  }

  public function testReturnsACurrentProjectPackage() {
    $this->assertInstanceOf('Webforge\Framework\Package\ProjectPackage', $package = $this->container->getProjectPackage());

    // ATTENTION: $this->languages has other content that $package->getLanguages() 
    // because package reads local config and ProjectPackage cannot be injected
  }

  public function testReturnsAnWebforgeTranslatorWithTheRightLocales() {
    $this->assertInstanceOf('Webforge\Translation\Translator', $translator = $this->container->getTranslator());
    $package = $this->container->getProjectPackage();

    $this->assertEquals(
      $package->getDefaultLanguage(),
      $translator->getLocale()
    );
  }

  public function testTranslatorTranslationAcceptance() {
    $this->container->getProjectPackage()->getConfiguration()->set(array('languaes'), $this->languages);

    $this->container->getProjectPackage()->getConfiguration()->set(array('translations'), Array(
      'de'=>Array(
        'hello'=>'Guten Tag'
      ),
      'en'=>Array(
        'hello'=>'Good Afternoon'
      )
    ));

    $this->assertEquals(
      'Guten Tag',
      $this->container->getTranslator()->trans('hello')
    );

    $this->container->setLanguage('en');

    $this->assertEquals('en', $this->container->getTranslator()->getLocale());

    $this->assertEquals(
      'Good Afternoon',
      $this->container->getTranslator()->trans('hello')
    );
  }
}