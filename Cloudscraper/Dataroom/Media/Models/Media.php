<?php
namespace Library\Cloudscraper\Dataroom\Media\Models;

class Media extends \Library\System\Model\AbstractModel
{
    const VENDOR_AMAZON  = "AmazonS3";
    
    const STATUS_ACTIVE = 1;
    
    const STATUS_INACTIVE = 0;
    
    /**
     *
     * @var integer @Primary
     *      @Identity
     *      @Column(type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string @Column(type="string", length=256, nullable=true)
     */
    public $name;

    /**
     *
     * @var string @Column(type="string", length=256, nullable=true)
     */
    public $description;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     */
    public $url;

    /**
     *
     * @var string @Column(type="string", length=256, nullable=true)
     */
    public $path;

    /**
     *
     * @var string @Column(type="string", length=256, nullable=true)
     */
    public $vendor;

    /**
     *
     * @var string @Column(type="string", length=512, nullable=true)
     */
    public $mime;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     */
    public $status;

    /**
     *
     * @var integer @Column(type="integer", length=10, nullable=false)
     */
    public $media_category_id;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     */
    public $metadata;

    /**
     *
     * @var integer @Column(type="integer", length=10, nullable=false)
     */
    public $created_by;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'CsxSectionMedia', 'media_id', [
            'alias' => 'CsxSectionMedia'
        ]);
        $this->belongsTo('created_by', '\USERMASTER', 'PK_USER_MASTER', [
            'alias' => 'USERMASTER'
        ]);
        $this->belongsTo('media_category_id', '\CsxMediaCategory', 'id', [
            'alias' => 'CsxMediaCategory'
        ]);
        /*
        $this->addBehavior(new \Phalcon\Mvc\Model\Behavior\Timestampable(array(
            'beforeValidationOnCreate' => array(
                'field' => 'created_at',
                'format' => 'Y-m-d H:i:s'
            )
        )));
        
        $this->addBehavior(new \Phalcon\Mvc\Model\Behavior\Timestampable(array(
            'beforeValidationOnCreate' => array(
                'field' => 'updated_at',
                'format' => 'Y-m-d H:i:s'
            )
        ))); 
        */
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_media';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMedia[]|Media
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Media
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
