<?php

namespace Psc\System\Console;

use Webforge\Common\System\File;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 *
 * $createCommand = function ($name, array|closure $configure, closure $execute, $help = NULL)
 * 
 * $arg = function ($name, $description = NULL, $required = TRUE, $multiple = FALSE) // default: required
 * $opt = function($name, $short = NULL, $withValue = TRUE, $description = NULL) // default: mit value required
 * $defOpt = function($name, $short = NULL, $default = NULL, $description = NULL) // with value and a default value
 * $flag = function($name, $short = NULL, $description) // ohne value
 */
class CommandsIncluder extends \Psc\SimpleObject {
  
  protected $commandsList;
  protected $file;
  protected $scope;

  public function __construct(File $file, Array $scope = array()) {
    $this->file = $file;
    $this->scope = $scope;
  }
  
  public function getCommands() {
    if (!isset($this->commandsList)) {
      $commands = array();
      if ($this->file->exists()) {

        $createCommand = function ($name, $configure, \Closure $execute, $help = NULL) use (&$commands) {
          if (is_array($configure)) {
            $definition = $configure;
            $configure = function ($cmd) use ($definition) {
              return $definition;
            };
          };
          
          $command = new ClosureCommand($name, $configure, $execute);
          $commands[] = $command;
          if (isset($help))
            $command->setDescription($help);
            
          return $command;
        };

        extract($this->buildArgsAPI());
        extract($this->scope);
        
        require $this->file;
      }
      
      $list = array();
      if (isset($commands) && is_array($commands)) {
        foreach ($commands as $command) {
          if ($command instanceof Command) {
            $list[] = $command; 
          }
        }
      }
      
      $this->commandsList = $list;
    }
    
    return $this->commandsList;
  }

  public function buildArgsAPI() {
    $arg = function ($name, $description = NULL, $required = TRUE, $multiple = FALSE) {
      $mode = $required ? InputArgument::REQUIRED : InputArgument::OPTIONAL;
      if ($multiple) {
        $mode |= InputArgument::IS_ARRAY;
      }
      return new InputArgument($name, $mode, $description);
    };
    
    $args = function ($name, $description = NULL, $required = TRUE) use ($arg) {
      return $arg($name, $description, $required, TRUE);
    };

    $opt = function ($name, $short = NULL, $withValue = TRUE, $description = NULL, $default = NULL) {
      return new InputOption($name, $short, $withValue ? InputOption::VALUE_REQUIRED : InputOption::VALUE_OPTIONAL, $description, $default);
    };

    $defOpt = function ($name, $short = NULL, $default = NULL, $description = NULL) {
      return new InputOption($name, $short, InputOption::VALUE_REQUIRED, $description, $default);
    };

    $flag = function ($name, $short = NULL, $description = NULL) {
      return new InputOption($name, $short, InputOption::VALUE_NONE, $description);
    };

    return compact('arg', 'args', 'opt', 'defOpt', 'flag');
  }
}
