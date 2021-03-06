Migration to 1.5
====================

## Major changes

Psc\CMS\Project:
  - Psc\CMS\Container does not return a Psc\CMS\Project on getProject() anymore. It returns a Webforge\Framework\Package\ProjectPackage instead (which also implements Webforge\Framework\Project). You have to  hint EVERYTHING for Webforge\Framework\Project.
  - the bootstrap function from project is no longer functional
  - no EVENT_BOOTSTRAPPED is triggered anymore

- The include path is no longer set automatically to src(!). Make sure that you can load everything with composer

## API changes

Psc\Doctrine\DCPackage
- Module is no longer optional

Psc\CMS\UploadManager
- use createForProject instead of empty constructor

Psc\CMS\Controller\FileUploadController
- UploadManager is required for constructor

Psc\CMS\Configuration
- class was removed. Use Webforge\Configuration\Configuration in place

ProjectMain:
 - getMainService from Project is no longer used

Psc\System\Process** has been deprecated
- use Webforge\Process\* classes where you can.

Psc\CMS\ContactFormMailer
- needs the configuration as first parameter now
- the debugRecipient can be set or not set (no development / no production flags anymore)

Client:
Use psc-cms-js >= 1.5.x@dev to keep the javascript running 

Psc\HTML\FrameworkPage
  - signature from addCMSDefaultCSS() changed. its now ($uiTheme, $jqUIVersion)

Psc\HTML\Page
  - cssManager and jsManager are removed from the hierarchy (this includes all attach* and other methods as well)
  - The Psc\CMS\Project references are switched into Webforge\Framework\Project

Psc\HTML\Page5, Psc\HTML\FrameworkPage
  - cssManager and jsManager were removed

Psc\CMS\Page
  - class was deleted

Psc\CSS\CSS
  - class was deleted

Psc\JS\JS
  - class was deprecated

Psc\CMS\ProjectMain
  - cssManager and jsManager were removed
  - css ui theme is no longer read from config

Psc\JS\RequirejsManager
  - class was deprecated

Psc\JS\jQuery
  - the class was shrinked to the minimum. See Webforge\DOM (webforge/dom) on packagist for details

Psc\PSC:
  - inProduction is deprecated
  - getLibrariesFilePath was removed
  - getHost() was removed
  - getCMS() is deprecated
  - getClassFile is deprecated
  - registerTools is removed
  - getAllClassFiles and getAllUsedClasSFiles are removed

Psc\Doctrine\Helper:
  - em() is deprecated

Psc\CMS\Controller\AbstractEntityController
  - DCPackage is no longer optional

Psc\Code\Test\EntityAsserter
  - class was removed due to inactivity
  - Psc\Doctrine\EntityDataRow was also removed

Psc\Doctrine\EntityBuilder
  - module is not optional  

Psc\Doctrine\Hydrator
  - second argument is DCPackage instead of em

Psc\Net\HTTP\FrontController
  - requesthandler argument is no longer optional

Psc\CMS\RequestDispatcher
  - HostConfig (parameter 3 for construct) is no longer optional

Psc\FE\Errors
  - was removed  

Psc\Code\Code
  - deprecated functions callback() and eval_callback() were removed

## CLI Commands  

  - create-test was removed (use webforge)
  - create-class was removed (use webforge)
  - create-joose was removed (use grunt)
  - build-phar command was removed (use the symfony compiler to build phars)

## Misc

  - Serveral classes dealing with phar archives were removed. Most building commands were removed.
  - EntityAsserter was removed