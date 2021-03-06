<?php

namespace Psc\TPL\ContentStream;

use RuntimeException;
use Doctrine\Common\Collections\Collection;

abstract class ContentStreamEntity extends \Psc\CMS\AbstractEntity implements ContentStream {

  public static function create($locale, $type = 'page-content', $revision = 'default', $slug = NULL) {
    $cs = new static($locale, $slug, $revision);
    $cs->setType($type ?: 'page-content');
    return $cs;
  }

  public static function unserialize(\stdClass $data, $entityFactory, Converter $converter) {
    $contentStream = self::create(
      $data->locale,
      isset($data->type) ? $data->type : NULL,
      isset($data->revision) ? $data->revision : NULL,
      isset($data->slug) ? $data->slug : NULL
    );

    $converter->convertUnserialized($data->entries, $contentStream);

    return $contentStream;
  }

  public function serialize($context, \Closure $serializeEntry) {
    $entries = array();

    foreach ($this->entries as $entry) {
      $entries[] = $serializeEntry($entry);
    }

    return (object) array(
      'type'=>$this->getType(),
      'locale'=>$this->locale,
      'type'=>$this->type,
      'revision'=>$this->revision,
      'entries'=>$entries
    );
  }

  public function getType() {
    return 'ContentStream';
  }

  public function getTemplateVariables(\Closure $exportEntry) {
    $vars = (object) array(
      //'locale'=>$this->locale,
      //'type'=>$this->type
      'partialRenderable'=>TRUE,
      'partialName'=>'contentstream'
    );

    $vars->entries = array();

    foreach ($this->entries as $entry) {
      $vars->entries[] = $exportEntry($entry);
    }

    return $vars;
  }

  /**
   * Returns the JSType for a class FQN
   */
  public static function convertClassName($classFQN) {
    return Code::getClassName($classFQN);
    //return Code::camelCaseToDash(Code::getClassName($classFQN));
  }

  /**
   * @param Psc\TPL\ContentStream\Entry $entry
   * @chainable
   */
  public function addEntry(Entry $entry) {
    if (!$this->entries->contains($entry)) {
      $this->entries->add($entry);
      $entry->setSort($this->entries->indexOf($entry)+1);
    }
    return $this;
  }

  /**
   * @param Doctrine\Common\Collections\Collection<CoMun\Entities\ContentStream\Entry> $entries
   */
  public function setEntries(Collection $entries) {
    $this->entries = $entries;
    foreach ($this->entries as $key => $entry) {
      $entry->setSort($key+1);
    }
    return $this;
  }

  /**
   * @param Psc\TPL\ContentStream\Entry $entry
   * @chainable
   */
  public function removeEntry(Entry $entry) {
    if ($this->entries->contains($entry)) {
      $this->entries->removeElement($entry);
    }
    return $this;
  }

  /**
   * @param Psc\TPL\ContentStream\Entry $entry
   * @return bool
   */
  public function hasEntry(Entry $entry) {
    return $this->entries->contains($entry);
  }

  /**
   *
   *  @inherit-doc
   * does not copy the revision. It will be created with default
   * the slug is prepended with "copy from"
   */
  public function createCopy() {
    $copy = self::create($this->locale, $this->type, 'default', 'copy from '.$this->slug);
    foreach ($this->entries as $entry) {
      $copy->addEntry($entry->createCopy());
    }

    return $copy;
  }


  /**
   * Gibt alle Elemente nach dem angegeben Element im CS zurück
   *
   * gibt es keine Element danach wird ein leerer Array zurückgegeben
   * gibt es das Element nicht, wird eine Exception geschmissen (damit ist es einfacher zu kontrollieren, was man machen will)
   * das element wird nicht im Array zurückgegeben
   * 
   * if $length is provided only $length elements will be returned
   * @return array
   * @throws RuntimeException
   */
  public function findAfter(Entry $entry, $length = NULL) {
    $entries = $this->entries->getValues(); // get entries correctly numbered
    $pos = array_search($entry, $entries, TRUE);

    if ($pos === FALSE) {
      throw new RuntimeException('Das Element '.$entry.' ist nicht im ContentStream. findAfter() ist deshalb undefiniert');
    }
    
    return array_merge(array_slice($entries, $pos+1, $length));
  }

  /**
   * Returns the element right after the given element
   * 
   * if no element is after this NULL will be returned
   * the $entry has to be in this contentstream otherwise an exception will thrown
   * 
   * @return Entry|NULL
   */
  public function findNextEntry(Entry $entry) {
    $list = $this->findAfter($entry, 1);

    if (count($list) === 1) {
      return current($list);
    }

    return NULL;
  }
  
  public function getContextLabel($context = self::CONTEXT_DEFAULT) {
    if (isset($this->slug)) {
      $label = sprintf('Seiteninhalt '.$this->slug);
    } else {
      $label = sprintf('Seiteninhalt #%d', $this->getIdentifier());
    }
    
    if (isset($this->locale)) {
      $label .= ' '.$this->locale;
    }
    return $label;
  }
  
  /**
   * Gibt das erste Vorkommen der Klasse im Stream zurück
   *
   * gibt es kein Vorkommen wird NULL zurückgegeben
   * @param string type ohne Namespace davor z.b. downloadlist für Downloadlist
   */
  public function findFirst($type, \Closure $withFilter = NULL) {
    $class = $this->getTypeClass($type);
    $withFilter = $withFilter ?: function () { return TRUE; };
    foreach ($this->entries as $entry) {
      if ($entry instanceof $class && $withFilter($entry)) {
        return $entry;
      }
    }
  }  

  public function __toString() {
    return sprintf("ContentStream(%s): %s:%s:%s\n", $this->slug, $this->locale, $this->type, $this->revision);
  }

  public function getDebug() {
    $ret = (string) $this;

    $entries = $this->getEntries();
    $ret .= sprintf("%d Entries:\n", count($entries));
    foreach ($entries as $entry) {
      $ret .= sprintf("  %s\n", $entry->getLabel());
    }

    return $ret;
  }

  abstract public function getTypeClass($typeName);
}

