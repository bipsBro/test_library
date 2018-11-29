<?php
namespace Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models;
class MediacategoryAcl extends \Library\System\Model\AbstractModel
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
     * @Column(type="integer", length=1, nullable=true)
     */
    public $default;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $media_category_slug;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $group_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $access_read;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $access_write;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $access_manage;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $access_download;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $access_delete;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('group_id', '\Library\Cloudscraper\Acl\Group\Models\Group', 'id', ['alias' => 'CsxGroup']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_mediaCategory_acl';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediacategoryAcl[]|MediacategoryAcl
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MediacategoryAcl
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
