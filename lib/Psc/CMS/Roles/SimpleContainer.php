<?php

namespace Psc\CMS\Roles;

use Psc\CMS\UploadManager;
use Psc\Image\Manager as ImageManager;
use Psc\Image\ManagerProvider as ImageManagerProvider;
use Psc\CMS\Controller\LanguageAware;

interface SimpleContainer extends LanguageAware, FQNSolver, ImageManagerProvider {

  public function setRevision($revision);

  public function getRevision();

  /**
   * @param Psc\Image\Manager
   */
  public function setImageManager(ImageManager $manager);

  /**
   * @return Psc\CMS\UploadManager
   */
  public function getUploadManager();
  public function setUploadManager(UploadManager $manager);


  public function getRoleRepository($roleName);
}
