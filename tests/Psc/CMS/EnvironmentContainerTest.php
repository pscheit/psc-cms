<?php

namespace Psc\CMS;

class EnvironmentContainerTest extends \Webforge\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = __NAMESPACE__.'\EnvironmentContainer';
    parent::setUp();

    $_SERVER['HTTP_HOST'] = '';
    $this->container = new EnvironmentContainer(array('session.init'=>FALSE));
  }

  /**
   * @dataProvider provideDependencies
   */
  public function testHasAllRequiredDependencies($name, $fqn) {
    $this->assertInstanceOf($fqn, $this->container->{'get'.$name}());
  }

  public function testWritesCookieDomainOption() {
    $this->assertEquals('', $this->container->getOption('cookies.domain'));

    $this->container->setOption('cookies.domain', 'my.domain.com');

    $this->assertEquals('my.domain.com', $this->container->getOption('cookies.domain'));
  }

  /**
   * @dataProvider provideDependencies
   */
  public function testInjectsAllDependencies($name, $fqn) {
    $mock = $this->getMockBuilder($fqn)->disableOriginalConstructor()->getMock();

    $this->assertChainable($this->container->{'set'.$name}($mock));

    $this->assertSame(
      $mock,
      $this->container->{'get'.$name}()
    );
  }

  public static function provideDependencies() {
    $tests = array();
  
    $test = function() use (&$tests) {
      $tests[] = func_get_args();
    };
  
    $test('Session', 'Webforge\Common\Session');
    $test('CookieManager', 'Psc\PHP\CookieManager');
  
    return $tests;
  }
}
