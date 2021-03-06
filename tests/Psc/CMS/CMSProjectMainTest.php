<?php

namespace Psc\CMS;

use Psc\Environment;
use Psc\PSC;
use Psc\Entities\User;

/**
 * @group class:Psc\CMS\ProjectMain
 *
 * dieser test war mal der alte CMS test
 */
class CMSProjectMainTest extends \Psc\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Psc\CMS\Projectmain';
    parent::setUp();
    
    $this->user = $this->createUser();
    $this->authController = $this->createAuthController($this->user);
    $this->environment = new Environment();
    $this->project = PSC::getProject();
    
    // inject a lot
    $this->cms = new ProjectMain($this->project, NULL, NULL, NULL, 10, NULL, $this->environment);
    $this->cms->setContainerClass('Psc\Test\CMS\Container');
    $this->cms->setAuthController($this->authController);
    
    $this->cms->init();
  }
  
  public function testConstruct() {
    $this->assertSame($this->authController, $this->cms->getAuthController());
    $this->assertSame($this->environment, $this->cms->getEnvironment());
    $this->assertSame($this->user, $this->authController->getUser());
  }
  
  public function testAuthControllerIsRunOnAuth() {
    $this->authController
      ->expects($this->once())
      ->method('run');
      
    $this->assertInstanceOf('Psc\CMS\Controller\AuthController',$this->cms->auth());
  }
  
  public function testMainHTMLPageGetsInjected() {
    $this->cms->auth(); // damit user gesetzt ist, sollte das vll mal eine exception schmeissen? (wenn getMainHTMLPage vor auth kommt?)
    
    $page = $this->cms->getMainHTMLPage();
    $this->assertInstanceOf('Psc\HTML\Page', $page);
    
    $samePage = $this->cms->getMainHTMLPage(); // wird nur einmal erstellt
    $this->assertSame($page,$samePage);
    
    // hier könnten wir noch als acceptance jede menge html asserten
  }
  
  protected function createAuthController($user) {
    $authController = $this->getMock('Psc\CMS\Controller\AuthController', array('run','setUserClass','getUser'), array(), '', FALSE);
    
    $authController->expects($this->any())
        ->method('getUser')
        ->will($this->returnValue($user));
    
    return $authController;
  }
  
  protected function createUser() {
    $user = new User();
    $user->setEmail('p.scheit@ps-webforge.com');
    $user->setPassword('blubb');
    return $user;
  }
}
?>