<?php
namespace Library\Cloudscraper\Dataroom\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use Library\System\Model\Behavior\Slug;

class  extends \Library\System\Model\AbstractModel
{
    /**
     * 
     * @var integer
     */
    const DELETED = 1;

    /**
     * 
     * @var integer
     */
    const ACTIVE = 1;

    /**
     * 
     * @var string
     */
    const DEAL_PUBLIC_MEDIACATEGORY_SLUG = 'marketing-documentation-deal';

    /**
     * 
     * @var string
     */
    const ASSET_PUBLIC_MEDIACATEGORY_SLUG = 'marketing-information-asset';
    
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

    /**
     *
     * @var integer @Primary
     *      @Identity
     *      @Column(type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string @Column(type="string", length=256, nullable=false)
     */
    public $name;

    /**
     *
     * @var string @Column(type="string", nullable=false)
     */
    public $section;

    /**
     *
     * @var string @Column(type="string", length=512, nullable=true)
     */
    public $slug;

    /**
     *
     * @var integer @Column(type="integer", length=10, nullable=true)
     */
    public $parent_id;

    /**
     *
     * @var integer @Column(type="integer", length=1, nullable=true)
     */
    public $status;

    /**
     *
     * @var integer @Column(type="integer", length=1, nullable=true)
     */
    public $default;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     */
    public $created_at;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     */
    public $updated_at;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $created_by;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("CSEXCHANGE");
        $this->addBehavior(new Timestampable(array(
            'beforeValidationOnCreate' => array(
                'field' => 'created_at',
                'format' => 'Y-m-d H:i:s'
            )
        )));
        
        $this->addBehavior(new Timestampable(array(
            'beforeValidationOnCreate' => array(
                'field' => 'updated_at',
                'format' => 'Y-m-d H:i:s'
            )
        )));
        
        $this->addBehavior(new Slug());
        
        $this->hasMany('id', 'CsxMedia', 'media_category_id', [
            'alias' => 'CsxMedia'
        ]);
        $this->hasMany('id', 'CsxMediaCategory', 'parent_id', [
            'alias' => 'CsxMediaCategory'
        ]);
        $this->belongsTo('created_by', '\USERMASTER', 'PK_USER_MASTER', [
            'alias' => 'USERMASTER'
        ]);
        $this->belongsTo('parent_id', '\CsxMediaCategory', 'id', [
            'alias' => 'CsxMediaCategory'
        ]);
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
     */
    public function checkCategoryExists($category, $section, $parent = null)
    {
        $db = \Phalcon\DI::getDefault()->get('db');
        
        $where = '';
        if (! is_null($parent)) {
            $where .= ' AND parent_id IS NOT NULL';
        } else if ($parent > 0) {
            $where .= ' AND parent_id = :parent';
        } else {
            $where .= ' AND parent_id IS NULL';
        }
        
        $query = $db->prepare("SELECT * FROM csx_mediaCategory WHERE name = :category AND section = :section {$where}");
        $query->execute([
            'category' => $category,
            'section' => $section
        ]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Returns string for slug. 
     * @return string
     */
    public function getSlug(){
        $slugString = sprintf("%s-%s",$this->name,$this->section);
        $phalconSlugLibrary = new \Phalcon\Utils\Slug();
        return $phalconSlugLibrary->generate($slugString);
    }
}
