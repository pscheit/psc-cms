<?php

namespace Psc\Code\Test;

use Psc\Code\Code;
use Webforge\Common\System\Dir;

/**
 *
 * Hilft einem beim Testen seine gemockten Resourcen/Fixtures whatever (wie Datenbank XML Files oder Files) zu finden
 */
class ResourceHelper extends \Psc\Object {
  
  protected $project;

  protected $usePersonalDirs;

  /**
   * @var string sub path to common dir in test-files (semantic location)
   */
  protected $commonDir;
  
  public function __construct(\Webforge\Framework\Project $project, $usePersonalDirs = NULL, $commonDir = NULL) {
    $this->project = $project;
    $this->usePersonalDirs = $usePersonalDirs ?: $this->project->getConfiguration()->get(array('tests', 'personal-directories'), FALSE);
    $this->commonDir = $commonDir ?: $this->project->getConfiguration()->get(array('tests', 'common-directory'), 'common/');
  }
  
  /**
   *
   * in einem PHPUnit - TestCase darf man dann das hier machen:
   * 
   * $baseDir = $resourceHelper->getBaseDirectory($this);
   *
   * Was dann in diesem Dir liegt bleibt dem Test überlassen
   */
  public function getTestDirectory(\PHPUnit_Framework_TestCase $test) {
    if ($this->usePersonalDirs) {
      $testName = Code::getClassName(get_class($test));
    
      return $this->getFixturesDirectory()->sub($testName.'/');
    } else {
      return $this->project->dir('test-files');
    }
  }
  
  /**
   * Gibt das Verzeichnis mit Testdaten zurück, die für alle Tests (per Projekt) gleich benutzt werden
   *
   */
  public function getCommonDirectory() {
    return $this->project->dir('test-files')->sub($this->commonDir);
  }
  
  /**
   * Gibt das Verzeichnis für die privaten Fixtures eines Tests zurück
   *
   * Der Default ist: {project}.base\files\testdata\fixtures
   * @return Webforge\Common\System\Dir
   */
  public function getFixturesDirectory() {
    return $this->project->dir('test-files')->sub('fixtures/');
  }
  
  /**
   * @deprecated use Doctrine Fixtures for this
   * @return array
   */
  public function getEntities($name) {
    // require nicht require_once sonst geht es nur in einem test :)
    require $file = $this->getCommonDirectory()->sub('entities/')->getFile($name.'.php');
    
    if (!isset($$name)) {
      throw new \Psc\Exception($file.' exportiert keine Variable $'.$name);
    }
    
    return $$name;
  }
}
