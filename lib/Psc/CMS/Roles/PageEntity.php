<?php

namespace Psc\CMS\Roles;

abstract class PageEntity extends \Psc\CMS\AbstractEntity implements \Psc\CMS\Roles\Page {
  
  /**
   * add this to the parent class with:
   * 
   * (at)ORM\PrePersist
   * (at)ORM\PreUpdate
   * public function updateTimestamps() {
   *   parent::updateTimestamps();
   * }
  */
  public function updateTimestamps() {
    if (!isset($this->created)) {
      $this->created = \Psc\DateTime\DateTime::now();
    }
    $this->modified = \Psc\DateTime\DateTime::now();
    
    return $this;
  }
  
  /**
   * @return CoMun\Entities\ContentStream[]
   */
  public function getContentStreamsByLocale($revision = 'default') {
    $contentStreams = array_filter($this->contentStreams->toArray(), function ($cs) use ($revision) {
      return $cs->getRevision() === $revision;
    });
    
    return \Psc\Doctrine\Helper::reindex($contentStreams, 'locale');
  }
  
  /**
   * @return CoMun\Entities\ContentStream
   */
  public function getContentStreamByLocale($locale, $revision = 'default') {
    $cs = $this->getContentStreamsByLocale($revision);
    return $cs[$locale];
  }
  
  /**
   * @return NavigationNode|NULL
   */
  public function getPrimaryNavigationNode() {
    return count($this->navigationNodes) > 0 ? $this->navigationNodes->get(0) : NULL;
  }
  
  public function getContextLabel($context = self::CONTEXT_DEFAULT) {
    if ($context === self::CONTEXT_GRID) {
      return $this->slug;
    }
    
    return $this->slug;
  }
}
?>