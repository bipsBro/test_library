<?php
namespace Library\Cloudscraper\Deal\Models;
class DealMaster extends \Library\System\Model\AbstractModel
{
    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_MASTER;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $DEAL_ID;

    /**
     *
     * @var string
     * @Column(type="string", length=300, nullable=false)
     */
    public $PROJECT_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $DEAL_TITLE;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $DEAL_SUB_TITLE;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $DEAL_WEBSITE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $PUBLISH;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $BRIEF_INTRODUCTION;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $LONGINTRODUCTION;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $REQUIRE_NDA;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_NDA_REQUIRED_FOR;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_NDASIGNATURE_OPTION;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_NDA_UPLOADS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_MODE;

    /**
     *
     * @var double
     * @Column(type="double", length=5, nullable=true)
     */
    public $STAKE_SIZE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $LPA_AVAILABLE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_STATUS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_UOM_TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_CURRENCY;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $FEATURED_IMAGE;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $UPLOAD_TEASER;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $MINISITE_URL;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $VIDEO_URL;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $DEAL_ANNOUNCEMENT;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $MISSING_MANDATORY_FIELDS_COUNT;

    /**
     *
     * @var double
     * @Column(type="double", length=5, nullable=false)
     */
    public $TOTAL_JV_INTEREST;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $SEARCH_PROFILE_MATCHES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $RUN_CRONJOB;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $EXISTING_PK_PROPERTY_MASTER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ALLOW_PUBLIC_FILES_DOWNLOAD;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_EXCLUSIVITY_STATUS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_STATUS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $REFINANCING_AUTO_DRIVE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $SHOW_TEASER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACTIVE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $ENTRY_USER_ID;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $CREATED_BY;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $UPDATED_BY;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $UPDATED_ON;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $RECORD_DATE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_DELETED;

    public $assets;
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("CSEXCHANGE");
        $this->hasMany('PK_DEAL_MASTER', 'Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection', 'deal_id', ['alias' => 'CsxMediaCategorySection']);
        $this->hasMany('PK_DEAL_MASTER', 'Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection', 'deal_id', ['alias' => 'CsxMediaMediaCategorySection']);
        $this->hasMany('PK_DEAL_MASTER', 'Library\Cloudscraper\Asset\Models\ConnectAssets', 'PK_DEAL_MASTER', ['alias' => 'ConnectAssets']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'DEAL_MASTER';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealMaster[]|DealMaster
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealMaster
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
    public function assets()
    {
        return $this->hasMany('PK_DEAL_MASTER', 'Library\Cloudscraper\Dataroom\Models\ConnectAssets', 'PK_DEAL_MASTER', ['alias' => 'ConnectAssets']);
    }

}
