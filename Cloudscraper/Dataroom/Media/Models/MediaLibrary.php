<?php
namespace Library\Cloudscraper\Dataroom\Media\Models;

class MediaLibrary extends \Library\System\Model\AbstractModel
{
    
    /**
     *
     * @var integer @Primary
     *      @Identity
     *      @Column(type="integer", length=11, nullable=false)
     */
    public $PK_MEDIA_LIBRARY;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $PK_HEADER_TABLE;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $PK_SUB_TABLE;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $PK_USER_FOLDERS;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $PK_PRIVATE_FOLDER;

    /**
     *
     * @var string @Column(type="string", length=500, nullable=false)
     */
    public $FILE_NAME;

    /**
     *
     * @var string @Column(type="string", length=250, nullable=false)
     */
    public $THUMB_MEDIA_LOCATION;

    /**
     *
     * @var string @Column(type="string", length=250, nullable=false)
     */
    public $MEDIA_LOCATION;

    /**
     *
     * @var string @Column(type="string", length=300, nullable=false)
     */
    public $MEDIA_DESCRIPTION;

    /**
     *
     * @var string @Column(type="string", length=200, nullable=false)
     */
    public $MEDIA_FOR;

    /**
     *
     * @var integer @Column(type="integer", length=1, nullable=false)
     */
    public $FOR_SHARED_FOLDER;

    /**
     *
     * @var integer @Column(type="integer", length=1, nullable=false)
     */
    public $ACTIVE;

    /**
     *
     * @var integer @Column(type="integer", length=11, nullable=false)
     */
    public $ENTRY_USER_ID;

    /**
     *
     * @var string @Column(type="string", nullable=false)
     */
    public $RECORD_DATE;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("CSEXCHANGE");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'MEDIA_LIBRARY';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MediaLibrary[]|MediaLibrary
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MediaLibrary
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
