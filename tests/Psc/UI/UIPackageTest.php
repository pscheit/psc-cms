<?php

namespace Psc\UI;

use Psc\Doctrine\TestEntities\Person;
use Psc\CMS\Action;
use Psc\CMS\ActionRouter;
use Psc\CMS\RequestMetaInterface;

class UIPackageTest extends \Psc\Doctrine\DatabaseTestCase {
  
  protected $ui;
  
  public function setUp() {
    $this->chainClass = 'Psc\CMS\UIPackageTest';
    parent::setUp();
    $this->ui = new UIPackage(
      new ActionRouter($this->dc),
      $this->dc
    );
  }
  
  public function testActionCreatesGenericGetAction() {
    $this->assertInstanceOf('Psc\CMS\Action', $action = $this->ui->action('person', 'GET', 'episodes'));
    $this->assertInstanceOf('Psc\CMS\EntityMeta', $action->getEntityMeta());
    $this->assertEquals('person', $action->getEntityMeta()->getEntityName());
  }

  public function testActionCreatesPersonSpecificEditAction() {
    $person = new Person('its me!');
    $this->assertInstanceOf('Psc\CMS\Action', $action = $this->ui->action($person, 'GET', 'form'));
    $this->assertSame($person, $action->getEntity());
  }
  
  public function testTabButtonCreatesATabButtonInterfaceButton() {
    $button = $this->ui->tabButton(
      'verknüpfte Personen anzeigen',
      $this->ui->action('person', 'GET', 'related')
    );

    $this->assertInstanceOf('Psc\UI\ButtonInterface', $button);
    $this->assertInstanceOf('Psc\UI\TabButtonInterface', $button);
    $this->assertEquals('verknüpfte Personen anzeigen', $button->getLabel());
  }
  
  public function testCreatedTabButtonHasTheMatchingRequestMetaForGivenAction() {
    $button = $this->ui->tabButton(
      'verknüpfte Personen anzeigen',
      $this->ui->action('person', 'GET', 'related')
    );
    
    $requestMeta = $button->getTabRequestMeta();
    
    $this->assertEquals(
      '/entities/persons/related',
      $requestMeta->getUrl()
    );
    
    $this->assertEquals(
      RequestMetaInterface::GET,
      $requestMeta->getMethod()
    );
  }
  
  public function testFullOIDSiteSyntax() {
    return;
    
    $designer = $this->ui;
    $components = $this->ui->getComponentsDesigner();
    
    $designer
      ->formPanel()
        ->action($oid, 'PUT')
        ->buttons(array('save','reload','save-close'))
        ->right()
          ->accordion
            ->section('Sounds & Texte', array(
              $designer->tabButton($soundIs),
              $designer->tabButton($soundIst)
            ))
            ->section('Wörterbuch', function () {
              $table = new \Psc\UI\Table();
              
              $table->tr();
              $table->td();
              // usw
              
              
              return array($table);
            })
            ->section('Meta', array(
              $componentDesigner->get($oid, 'label'),
              $componentDesigner->componentFor($oid, 'sounds'),
              $componentDesigner->componentFor($oid, 'pages')
            ))
            ->section('Optionen', array(
              $designer->button('OID löschen')
                ->action($oid, 'DELETE')
            ))
//          ->end()
//        ->end()
        ->left()
          ->group('Tippreihenfolge für Entdecken')
            ->group('1. Tippen')
              ->group('Act0')->end()
              ->checkbox('Random?', 'random')
              ->appendButton('weitere Aktion')
                ->action($product, 'GET', array('matrix-manager','create-action'))
              ->end()
          ->appendButton('weiterer Tipp')
            ->action($product, 'GET', array('matrix-manager','create-action'))
            
    
    ;
  }
}
?>