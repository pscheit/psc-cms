<?php

namespace Psc\Net;

use Psc\Code\Code;
use Webforge\Common\System\File;

/**
 * Ein simplifizierter Request den der RequestHandler an den Service schickt
 * 
 * hier ist von HTTP nichts mehr zu sehen
 */
class ServiceRequest extends \Psc\SimpleObject {
  
  /**
   * Request Parts
   * @var string[] eine Liste von Strings die den Request darstellen
   */
  protected $parts;
  
  /**
   * @var const Service::GET|Service::PUT|Service::POST|Service::PATCH
   */
  protected $type;
  
  /**
   * Der Inhalt des Requests
   * 
   * @var mixed
   */
  protected $body;
  
  /**
   * Die QueryParameter des Requests
   * 
   * @var array|NULL
   */
  protected $query;
  
  /**
   * @var Webforge\Common\System\File[]
   */
  protected $files;

  /**
   * Zusätzliche Meta Daten aus dem Request
   * 
   * @var array
   */
  protected $meta;
  
  public function __construct($type, Array $parts = array(), $body = NULL, Array $query = NULL, Array $files = array(), Array $meta = NULL) {
    $this->parts = $parts;
    $this->setType($type);
    $this->body = $body;
    $this->query = $query;
    $this->files = $files;
    $this->meta = $meta ? (array) $meta : array();
  }
  
  /**
   * @param $type const Service::GET|Service::PUT|Service::POST|Service::DELETE
   */
  public static function create($type, $parts, $body = NULL) {
    return new static($type, $parts, $body);
  }
  
  /**
   * @param const $type Service::
   * @chainable
   */
  public function setType($type) {
    Code::value($type, Service::GET, Service::POST, Service::PUT, Service::DELETE, Service::PATCH);
    $this->type = $type;
    return $this;
  }
  
  /**
   * @return Service::
   */
  public function getType() {
    return $this->type;
  }
  
  /**
   * @param array $parts
   * @chainable
   */
  public function setParts(Array $parts) {
    $this->parts = $parts;
    return $this;
  }
  
  /**
   * @return array
   */
  public function getParts() {
    return $this->parts;
  }
  
  /**
   * @param mixed $body
   * @chainable
   */
  public function setBody($body) {
    $this->body = $body;
    return $this;
  }
  
  /**
   * @return mixed
   */
  public function getBody() {
    return $this->body;
  }
  
  /**
   * @param array $query
   * @chainable
   */
  public function setQuery(Array $query) {
    $this->query = $query;
    return $this;
  }
  
  /**
   * @return array|NULL
   */
  public function getQuery() {
    return $this->query;
  }
  
  /**
   * @return bool
   */
  public function hasQuery() {
    return count($this->query) > 0;
  }
  
  /**
   * @return bool
   */
  public function hasMeta($key) {
    return array_key_exists($key, $this->meta);
  }
  
  /**
   * @return mixed
   */
  public function getMeta($key) {
    return $this->hasMeta($key) ? $this->meta[$key] : NULL;
  }
  
  /**
   * Sets meta data
   * 
   * @param string|int $key
   * @param scalar $value
   */
  public function setMeta($key, $value) {
    $this->meta[$key] = $value;
    return $this;
  }
  
  /**
   * @return string
   */
  public function debug() {
    return sprintf('%s /%s%s',
                   $this->type,
                   implode('/',$this->parts),
                   ($this->hasQuery() ? '/?'.http_build_query($this->query) : NULL)
                  );
  }
  
  /**
   * @param Webforge\Common\System\File[] $files
   */
  public function setFiles(Array $files) {
    $this->files = $files;
    return $this;
  }
  
  /**
   * @return Webforge\Common\System\File[]
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * @return Webforge\Common\System\File
   */
  public function getFile($name) {
    return isset($this->files[$name]) ? $this->files[$name] : NULL;
  }
  
  /**
   * @return bool
   */
  public function hasFiles() {
    return count($this->files) > 0;
  }
}
?>