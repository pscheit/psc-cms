<?php

namespace Psc\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;


class DateType extends \Doctrine\DBAL\Types\DateType {

  public function convertToPHPValue($value, AbstractPlatform $platform) {
    if ($value === null) {
      return null;
    }

    $val = \Psc\DateTime\Date::parse($platform->getDateFormatString(), $value);
    if (!$val) {
      throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateFormatString());
    }
    
    return $val;
  }
  
  public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
    return sprintf("%s COMMENT '%s' ",
                   parent::getSQLDeclaration($fieldDeclaration, $platform),
                   $platform->getDoctrineTypeComment($this)
                  );
  }
  
  public function getName() {
    return 'PscDate';
  }
}
?>