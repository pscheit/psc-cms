<?php

namespace Psc\Doctrine;

use Psc\Doctrine\TypeExporter;
use Psc\Data\Type\Type;

/**
 * @group class:Psc\Doctrine\TypeExporter
 */
class TypeExporterTest extends \Psc\Code\Test\Base {
  
  protected $exporter;

  public function setUp() {
    $this->chainClass = 'Psc\Doctrine\TypeExporter';
    parent::setUp();
    $this->exporter = $this->createTypeExporter();
  }

  public function testConstruct() {
    $this->assertInstanceOf('Psc\Data\Type\Exporter',$this->createTypeExporter());
  }
  
  /**
   * @dataProvider provideTestExport
   */
  public function testExport($expectedExport, Type $type) {
    $this->assertEquals($expectedExport, $this->exporter->exportType($type));
  }
  
  public static function provideTestExport() {
    $tests = array();
    
    $test = function ($type, $expectedExport) use (&$tests) {
      if (!($type instanceof Type))
        $type = Type::create($type);
      $tests[] = array($expectedExport,$type);
    };
    
    $test('String','string');
    $test('Integer','integer');
    $test('Boolean','boolean');
    $test('DateTime','PscDateTime');
    $test('Email','string');
    
    // @TODO Regression: 2 mal DCEnumType darf nicht gecached werden

    return $tests;
  }
  
  /**
   * @expectedException Psc\Data\Type\TypeExportException
   * @dataProvider provideExportException
   */
  public function testExportException($type) {
    $this->exporter->exportType($type);
  }
  
  public static function provideExportException() {
    $tests = array();
    $test = function ($type) use (&$tests) {
      if (!($type instanceof Type))
        $type = Type::create($type);
      $tests[] = array($type);
    };
    
    $test('FailingTest');

    return $tests;
  }

  /**
   * @expectedException Psc\Data\Type\TypeConversionException
   */
  public function testGetPscTypeException() {
    $this->exporter->getPscType('notKnown');
  }


  public function createTypeExporter() {
    return new TypeExporter();
  }
}
?>