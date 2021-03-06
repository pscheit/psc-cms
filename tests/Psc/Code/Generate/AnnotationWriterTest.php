<?php

namespace Psc\Code\Generate;

use \Psc\Code\Generate\Annotation;
use ReflectionClass;
use Doctrine\Common\Annotations\DocParser;

/**
 * @group class:Psc\Code\Generate\AnnotationWriter
 * @group generate
 */
class AnnotationWriterTest extends \Psc\Doctrine\DatabaseTestCase {
  
  protected $annotationReader;
  
  public function setUp() {
    parent::setUp();
    
    $this->annotationReader = $this->module->createAnnotationReader();
    $this->chainClass = 'Psc\Code\Generate\AnnotationWriter';
    
    $this->parser = new DocParser;
    $this->parser->addNamespace('Doctrine\ORM\Mapping');
    
    // autoloading:
    class_exists('Psc\Code\Compile\Annotations\Compiled', true);
    class_exists('Psc\Code\Compile\Annotations\Property', true);
  }
  
  protected function parse($docText) {
    $annotations = $this->parser->parse($docText);
    $this->assertInternalType('array',$annotations);
    return $annotations;
  }
  
  /**
   * @dataProvider provideTestSingleAnnotation_DefaultWriter
   */
  public function testSingleAnnotation_DefaultWriter($docText) {
    $annotations = $this->parse($docText);
    $this->assertCount(1,$annotations);
    $annotation = current($annotations);

    $this->assertEquals($docText, $this->createDefaultWriter()->writeAnnotation($annotation));
  }
  
  
  public static function provideTestSingleAnnotation_DefaultWriter() {
    $tests = array();
    
    // testSinglePlainValue
    $tests[] = '@Column(type="integer")';
    
    // testBoolValue
    $tests[] = '@Column(nullable=true)';
    
    // single flat plain
    $tests[] = '@ChangeTrackingPolicy("NOTIFY")';

    // without values
    $tests[] = '@Column';
    
    // verschachtelt mit doctrine
    $tests[] = '@Table(name="test_tags", uniqueConstraints={@UniqueConstraint(name="label", columns={"label"})})';

    // nach beberlei, soll das hier falsch sein (nach grammatik aus den Parser-Comments irgendwie nicht
    //$tests[] = '@\Psc\Code\Generate\ComplexAnnotation("pv1", "pv2", "pv3", "rootValue1")';
    
    // orderBy hier für brauchen wirs dann (mit value)
    $tests[] = '@OrderBy({"someField"="ASC"})';
    
    // selbst von doctrine abgeleitete annotation (siehe unten)
    $tests[] = '@\Psc\Code\Generate\ComplexAnnotation(root1Key="rootValue2", root2Key="rootValue1")';
    
    // eigene Psc-Annotations
    $tests[] = '@\Psc\Code\Compile\Annotations\Compiled';
    $tests[] = '@\Psc\Code\Compile\Annotations\Property(name="prop1name")';
    $tests[] = '@\Psc\Code\Compile\Annotations\Property(name="prop1name", getter=false)';
    
    
    $tests = array_map(function ($arg) { return array($arg); }, $tests);
    return $tests;
  }
  
  public function testChainables() {
    $writer = $this->createWriter();
    $this->assertChainable($writer->setDefaultAnnotationNamespace('Psc\Doctrine'));
    $this->assertChainable($writer->setAnnotationNamespaceAlias('Psc','Psc\Doctrine'));
  }
  
  public function testGetAnnotationName_withDefaultNamespace() {
    $writer = $this->createWriter()->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping');
    $annotation = new \Doctrine\ORM\Mapping\Version(array());
    $this->assertEquals('Version',$writer->getAnnotationName($annotation));
  }
  
  public function testExtractValues() {
    $writer = $this->createWriter()->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping');
    $annotation = new \Doctrine\ORM\Mapping\Column();
    $annotation->name = 'testcolumn';
    $this->assertEquals(array('name'=>'testcolumn'), $writer->extractValues($annotation));
  }
  
  
  public function testAliasWriting() {
    $this->assertEquals('@ORM\Entity', $this->createAliasWriter()
                                          ->writeAnnotation(
                                            \Psc\Doctrine\Annotation::createDC('Entity')
                                          )
                        );
  }
  
  public function createWriter() {
    return new AnnotationWriter();    
  }
  
  public function createDefaultWriter() {
    return $this->createWriter()->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping');
  }
  
  public function createAliasWriter() {
    return $this->createWriter()->setAnnotationNamespaceAlias('Doctrine\ORM\Mapping', 'ORM');
  }
}

/**
 * @Annotation
 */
class ComplexAnnotation extends \Doctrine\Common\Annotations\Annotation implements \Doctrine\ORM\Mapping\Annotation {
  public $root1Key;
  public $root2Key;
}

class AnnotatedClass {
  
  /**
   * @\Psc\Code\Generate\ComplexAnnotation({"pv1","pv2","pv3","rootKey1"="rootValue1"})
   */
  protected $property1;
  
}
