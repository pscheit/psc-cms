<?php

namespace Psc\Doctrine\TestEntities;

use Doctrine\Common\Collections\Collection;
use Psc\Data\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class CompiledCategory extends \Psc\CMS\AbstractEntity {
  
  /**
   * @var integer
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  protected $id;
  
  /**
   * @var string
   * @ORM\Column
   */
  protected $label;
  
  /**
   * @var Doctrine\Common\Collections\Collection<Psc\Doctrine\TestEntities\Article>
   * @ORM\OneToMany(mappedBy="category", targetEntity="Psc\Doctrine\TestEntities\Article")
   */
  protected $articles;
  
  public function __construct($label) {
    $this->setLabel($label);
    $this->articles = new \Psc\Data\ArrayCollection();
  }
  
  /**
   * @return integer
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * Gibt den Primärschlüssel des Entities zurück
   * 
   * @return mixed meistens jedoch einen int > 0 der eine fortlaufende id ist
   */
  public function getIdentifier() {
    return $this->id;
  }
  
  /**
   * @param mixed $identifier
   * @chainable
   */
  public function setIdentifier($id) {
    $this->id = $id;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getLabel() {
    return $this->label;
  }
  
  /**
   * @param string $label
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Psc\Doctrine\TestEntities\Article>
   */
  public function getArticles() {
    return $this->articles;
  }
  
  /**
   * @param Doctrine\Common\Collections\Collection<Psc\Doctrine\TestEntities\Article> $articles
   */
  public function setArticles(Collection $articles) {
    $this->articles = $articles;
    return $this;
  }
  
  /**
   * @param Psc\Doctrine\TestEntities\Article $article
   * @chainable
   */
  public function addArticle(Article $article) {
    if (!$this->articles->contains($article)) {
      $this->articles->add($article);
    }
    return $this;
  }
  
  /**
   * @param Psc\Doctrine\TestEntities\Article $article
   * @chainable
   */
  public function removeArticle(Article $article) {
    if ($this->articles->contains($article)) {
      $this->articles->removeElement($article);
    }
    return $this;
  }
  
  /**
   * @param Psc\Doctrine\TestEntities\Article $article
   * @return bool
   */
  public function hasArticle(Article $article) {
    return $this->articles->contains($article);
  }
  
  public function getEntityName() {
    return 'Psc\Doctrine\TestEntities\CompiledCategory';
  }
  
  public static function getSetMeta() {
    return new \Psc\Data\SetMeta(array(
      'id' => new \Webforge\Types\IdType(),
      'label' => new \Webforge\Types\StringType(),
      'articles' => new \Webforge\Types\PersistentCollectionType(new \Psc\Code\Generate\GClass('Psc\\Doctrine\\TestEntities\\Article')),
    ));
  }
}
?>