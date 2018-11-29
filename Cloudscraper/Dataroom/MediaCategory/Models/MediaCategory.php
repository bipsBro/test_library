<?php
namespace Library\Cloudscraper\Dataroom\MediaCategory\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use Library\System\Model\Behavior\Slug;

class MediaCategory extends \Library\System\Model\AbstractModel
{
    /**
     * 
     * @var string
     */
    const DEAL_PUBLIC_MEDIACATEGORY_SLUG = 'marketing-documentation-deal-1';

    /**
     * 
     * @var string
     */
    const ASSET_PUBLIC_MEDIACATEGORY_SLUG = 'marketing-information-asset-1';
    
    /**
     * 
     * @var string
     */
    const SECTION_ASSET = "ASSET";
    
    /**
     * 
     * @var string
     */
    const SECTION_DEAL = "DEAL";
    
    /**
     * Syndication deal id.
     */
    const SECTION_SYNDICATION_DEAL = "SYNDICATION_DEAL";
    
    const CATEGORY_DEFAULT_TRUE = 1;
    
    const CATEGORY_DEFAULT_FALSE = 0;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=256, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=512, nullable=true)
     */
    public $slug;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $section;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $section_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    public $parent_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    public $nesting_depth;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $default;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $updated_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $created_by;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {   
        $this->addBehavior(new Slug());
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_mediaCategory';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediaCategory[]|CsxMediaCategory
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediaCategory
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     *
     * @param string $category
     * @param string $section
     * @param null/string $parent
     * @return array
      
    public function checkCategoryExists($category, $section, $parent = null)
    {
        $db = \Phalcon\DI::getDefault()->get('db');
        
        $where = '';
        if (! is_null($parent)) {
            $where .= ' AND parent_id  > 0';
        } else if ($parent > 0) {
            $where .= ' AND parent_id = :parent';
        } else {
            $where .= ' AND parent_id = 0';
        }
        
        $query = $db->prepare("SELECT * FROM csx_mediaCategory WHERE name = :category AND section = :section {$where}");
        $query->execute([
            'category' => $category,
            'section' => $section
        ]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
     */
  
    
    /**
     * Returns string for slug. 
     * @return string
     */
    public function getSlug(){
        $slugString = sprintf("%s-%s-%s",$this->name,$this->section,$this->nesting_depth);
        $phalconSlugLibrary = new \Phalcon\Utils\Slug();
        return $phalconSlugLibrary->generate($slugString);
    }
    
    public function getPublicMediaCategoryId(){
        
    }
}
