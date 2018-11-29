<?php
namespace Library\Cloudscraper\Dataroom\MediaMediaCategory\Models;

class MediaMediaCategory extends \Library\System\Model\AbstractModel
{
    
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
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $media_id;
    
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $media_category_id;
    
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
     * @Column(type="integer", length=10, nullable=false)
     */
    public $created_by;
    
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
     * @Column(type="integer", length=1, nullable=true)
     */
    public $status;
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('media_id', '\Library\Cloudscraper\Dataroom\Media\Models\Media', 'id');
    }
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_media_mediaCategory';
    }
    
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediaMediacategory[]|CsxMediaMediacategory
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediaMediacategory
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
}
