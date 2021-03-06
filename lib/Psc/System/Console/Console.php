<?php

namespace Psc\System\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Webforge\Framework\Project;

class Console extends \Psc\Object {
  
  protected $cli;
  
  protected $output;
  
  protected $name;
  protected $version;
  
  protected $doctrine;
  protected $project;
  
  public function __construct(Application $application = NULL, \Psc\Doctrine\Module $doctrine = NULL, Project $project = NULL) {
    $this->name = 'Psc Command Line Interface';
    $this->version = NULL;
    $this->cli = $application ?: new Application($this->name, $this->version);
    $this->cli->setCatchExceptions(true);
    $this->cli->setHelperSet(new HelperSet(array(
      'dialog' => new \Symfony\Component\Console\Helper\DialogHelper($warnDeprecation = false)
    )));
    $this->project = $project ?: \Psc\PSC::getProject();
    $this->doctrine = $doctrine ?: $GLOBALS['env']['container']->getModule('Doctrine');
    $this->setUp();
  }

  protected function setUp() {
    $this->cli->getHelperSet()
      ->set(new \Psc\System\Console\ProjectHelper($this->project), 'project');

    if (isset($this->doctrine)) {
      $em = $this->doctrine->getEntityManager();
      $this->cli->getHelperSet()
        ->set(new \Psc\System\Console\DoctrinePackageHelper(new \Psc\Doctrine\DCPackage($this->doctrine, $em)), 'dc');
      $this->cli->getHelperSet()
        ->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection(), 'db'));
      $this->cli->getHelperSet()
        ->set(new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em), 'em');
    }
  }
  
  public function addCommands() {
    $this->cli->addCommands(array_merge(array(
      new \Psc\System\Console\CreateUserCommand(),
      
      new CompileTestEntitiesCommand(),
      
      new AddClassPropertyCommand(),
      new ArrayCollectionInterfaceCompileCommand(),
      new GenericCompileCommand(),
      new TriggerFileChangedCommand(),
      
      new \Psc\System\Console\ORMSchemaCommand(),
      new \Psc\System\Console\ORMCreateEntityCommand(),
      
      new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
      new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    ), $this->includeCommands()));
  }

  public function run() {
    
    $this->output = new WindowsConsoleOutput();
    $this->output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
    
    $this->addCommands();
    $this->cli->run(NULL, $this->output);
  }
  
  protected function getHelperSetArray() {
    return array(
      
    );
  }
  
  protected function includeCommands() {
    $file = $this->project->dir('lib')->getFile('inc.commands.php');

    $inc = new CommandsIncluder($file);
    return $inc->getCommands();
  }
}
