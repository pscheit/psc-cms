<?php

namespace Psc\Code;

interface CallableObject {
  
  /**
   * Ruft das Objekt auf
   *
   * $arguments ist eine Liste der Argumente (beginnt somit mit Schlüssel 0)
   */
  public function call(Array $arguments = array());
  
}
?>