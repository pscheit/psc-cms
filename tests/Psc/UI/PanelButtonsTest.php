<?php

namespace Psc\UI;

/**
 * @group class:Psc\UI\PanelButtons
 */
class PanelButtonsTest extends \Psc\Code\Test\HTMLTestCase {
  
  protected $panelButtons;
  
  public function setUp() {
    $this->chainClass = 'Psc\UI\PanelButtons';
    parent::setUp();
    
  }
  
  public function testAcceptance() {
    $this->panelButtons = new PanelButtons(array('save','reload','save-close'));
    
    $this->html = $this->panelButtons->html();
    
    $this->test->css('div.psc-cms-ui-buttonset.psc-cms-ui-buttonset-right')
      ->count(1)
      ->test('button.psc-cms-ui-button-left.psc-cms-ui-button-save')->count(1)->end()
      ->test('button.psc-cms-ui-button-left.psc-cms-ui-button-reload')->count(1)->end()
      ->test('button.psc-cms-ui-button-left.psc-cms-ui-button-save-close')->count(1)->end()
    ;
      
    $this->test->css('div.psc-cms-ui-buttonset.psc-cms-ui-buttonset-right + div.clear')
      ->count(1);
  }
  
  public function testAddNewButtonWithOwnButtonInstance() {
    $this->panelButtons = new PanelButtons(array('reload'));
    $newButton = $this->panelButtons->addNewButton(new Button('My Nice New Button'));
    $this->assertInstanceof('Psc\UI\ButtonInterface', $newButton);
    
    $this->assertNotEmpty($newButton->getLeftIcon());
  }
  
  /**
   * @expectedException InvalidArgumentException
   */
  public function testInvalidButtonsParam() {
    new PanelButtons(array('save','save-close','relod')); // look closely
  }
}
?>