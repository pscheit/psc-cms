<?php

namespace Psc\Entities;

use Psc\DateTime\DateTime;
use Doctrine\Common\Collections\Collection;
use Psc\Data\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class CompiledNavigationNode extends \Psc\CMS\Roles\NavigationNodeEntity {
  
  /**
   * @var integer
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  protected $id;
  
  /**
   * @var array
   */
  protected $i18nTitle = array();
  
  /**
   * @var string
   * @ORM\Column
   */
  protected $titleDe;
  
  /**
   * @var array
   */
  protected $i18nSlug = array();
  
  /**
   * @var string
   * @ORM\Column
   */
  protected $slugDe;
  
  /**
   * @var integer
   * @ORM\Column(type="integer")
   */
  protected $lft;
  
  /**
   * @var integer
   * @ORM\Column(type="integer")
   */
  protected $rgt;
  
  /**
   * @var integer
   * @ORM\Column(type="integer")
   */
  protected $depth;
  
  /**
   * @var string
   * @ORM\Column(nullable=true)
   */
  protected $image;
  
  /**
   * @var Psc\DateTime\DateTime
   * @ORM\Column(type="PscDateTime")
   */
  protected $created;
  
  /**
   * @var Psc\DateTime\DateTime
   * @ORM\Column(type="PscDateTime")
   */
  protected $updated;
  
  /**
   * @var string
   * @ORM\Column
   */
  protected $context = 'default';
  
  /**
   * @var Psc\Entities\Page
   * @ORM\ManyToOne(targetEntity="Psc\Entities\Page", inversedBy="navigationNodes")
   * @ORM\JoinColumn(onDelete="SET NULL")
   */
  protected $page;
  
  /**
   * @var Doctrine\Common\Collections\Collection<Psc\Entities\NavigationNode>
   * @ORM\OneToMany(mappedBy="parent", targetEntity="Psc\Entities\NavigationNode")
   */
  protected $children;
  
  /**
   * @var Psc\Entities\NavigationNode
   * @ORM\ManyToOne(targetEntity="Psc\Entities\NavigationNode", inversedBy="children")
   * @ORM\JoinColumn(onDelete="SET NULL")
   */
  protected $parent;
  
  public function __construct() {
    $this->children = new \Psc\Data\ArrayCollection();
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
   * @return array
   */
  public function getI18nTitle() {
    if (count($this->i18nTitle) == 0) {
      $this->i18nTitle['de'] = $this->titleDe;
    }
    return $this->i18nTitle;
  }
  
  /**
   * @param array $i18nTitle
   */
  public function setI18nTitle(Array $i18nTitle) {
    $this->i18nTitle = $i18nTitle;
    $this->titleDe = $this->i18nTitle['de'];
    return $this;
  }
  
  public function getTitle($lang) {
    $title = $this->getI18nTitle();
    return $title[$lang];
  }
  
  public function setTitle($value, $lang) {
    if ($lang === 'de') {
      $this->titleDe = $this->i18nTitle['de'] = $value;
    }
    return $this;
  }
  
  /**
   * @return array
   */
  public function getI18nSlug() {
    if (count($this->i18nSlug) == 0) {
      $this->i18nSlug['de'] = $this->slugDe;
    }
    return $this->i18nSlug;
  }
  
  /**
   * @param array $i18nSlug
   */
  public function setI18nSlug(Array $i18nSlug) {
    $this->i18nSlug = $i18nSlug;
    $this->slugDe = $this->i18nSlug['de'];
    return $this;
  }
  
  public function getSlug($lang) {
    $title = $this->getI18nSlug();
    return $title[$lang];
  }
  
  public function setSlug($value, $lang) {
    if ($lang === 'de') {
      $this->slugDe = $this->i18nSlug['de'] = $value;
    }
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getLft() {
    return $this->lft;
  }
  
  /**
   * @param integer $lft
   */
  public function setLft($lft) {
    $this->lft = $lft;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getRgt() {
    return $this->rgt;
  }
  
  /**
   * @param integer $rgt
   */
  public function setRgt($rgt) {
    $this->rgt = $rgt;
    return $this;
  }
  
  /**
   * @return integer
   */
  public function getDepth() {
    return $this->depth;
  }
  
  /**
   * @param integer $depth
   */
  public function setDepth($depth) {
    $this->depth = $depth;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getImage() {
    return $this->image;
  }
  
  /**
   * @param string $image
   */
  public function setImage($image) {
    $this->image = $image;
    return $this;
  }
  
  /**
   * @return Psc\DateTime\DateTime
   */
  public function getCreated() {
    return $this->created;
  }
  
  /**
   * @param Psc\DateTime\DateTime $created
   */
  public function setCreated(DateTime $created) {
    $this->created = $created;
    return $this;
  }
  
  /**
   * @return Psc\DateTime\DateTime
   */
  public function getUpdated() {
    return $this->updated;
  }
  
  /**
   * @param Psc\DateTime\DateTime $updated
   */
  public function setUpdated(DateTime $updated) {
    $this->updated = $updated;
    return $this;
  }
  
  /**
   * @return string
   */
  public function getContext() {
    return $this->context;
  }
  
  /**
   * @param string $context
   */
  public function setContext($context) {
    $this->context = $context;
    return $this;
  }
  
  /**
   * @return Psc\Entities\Page
   */
  public function getPage() {
    return $this->page;
  }
  
  /**
   * @param Psc\Entities\Page $page
   */
  public function setPage(Page $page = NULL) {
    $this->page = $page;
    if (isset($page)) $page->addNavigationNode($this);

    return $this;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection<Psc\Entities\NavigationNode>
   */
  public function getChildren() {
    return $this->children;
  }
  
  /**
   * @param Doctrine\Common\Collections\Collection<Psc\Entities\NavigationNode> $children
   */
  public function setChildren(Collection $children) {
    $this->children = $children;
    return $this;
  }
  
  /**
   * @param Psc\Entities\NavigationNode $child
   * @chainable
   */
  public function addChild(NavigationNode $child) {
    if (!$this->children->contains($child)) {
      $this->children->add($child);
    }
    return $this;
  }
  
  /**
   * @param Psc\Entities\NavigationNode $child
   * @chainable
   */
  public function removeChild(NavigationNode $child) {
    if ($this->children->contains($child)) {
      $this->children->removeElement($child);
    }
    return $this;
  }
  
  /**
   * @param Psc\Entities\NavigationNode $child
   * @return bool
   */
  public function hasChild(NavigationNode $child) {
    return $this->children->contains($child);
  }
  
  /**
   * @return Psc\Entities\NavigationNode
   */
  public function getParent() {
    return $this->parent;
  }
  
  /**
   * @param Psc\Entities\NavigationNode $parent
   */
  public function setParent(NavigationNode $parent = NULL) {
    $this->parent = $parent;
    return $this;
  }
  
  public function getEntityName() {
    return 'Psc\Entities\CompiledNavigationNode';
  }
  
  public static function getSetMeta() {
    return new \Psc\Data\SetMeta(array(
      'id' => new \Psc\Data\Type\IdType(),
      'i18nTitle' => new \Psc\Data\Type\I18nType(new \Psc\Data\Type\StringType(), array('de')),
      'i18nSlug' => new \Psc\Data\Type\I18nType(new \Psc\Data\Type\StringType(), array('de')),
      'lft' => new \Psc\Data\Type\PositiveIntegerType(),
      'rgt' => new \Psc\Data\Type\PositiveIntegerType(),
      'depth' => new \Psc\Data\Type\PositiveIntegerType(),
      'image' => new \Psc\Data\Type\ImageType(),
      'created' => new \Psc\Data\Type\DateTimeType(),
      'updated' => new \Psc\Data\Type\DateTimeType(),
      'context' => new \Psc\Data\Type\StringType(),
      'page' => new \Psc\Data\Type\EntityType(new \Psc\Code\Generate\GClass('Psc\\Entities\\Page')),
      'children' => new \Psc\Data\Type\PersistentCollectionType(new \Psc\Code\Generate\GClass('Psc\\Entities\\NavigationNode')),
      'parent' => new \Psc\Data\Type\EntityType(new \Psc\Code\Generate\GClass('Psc\\Entities\\NavigationNode')),
    ));
  }
}
?>