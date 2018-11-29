<?php
namespace Library\Cloudscraper\Dataroom\Models;

class PropertyMaster extends \Phalcon\Mvc\Model
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
    public $PK_PROPERTY_MASTER;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $PROPERTY_ID;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_PROPERTY_TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $PK_TYPE_OF_PROPERTY;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $PROPERTY_NAME;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $IS_FISHING_DEAL;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $NO_OF_STRUCTURES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $LANDMARK;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $LANDMARK_DETAILS;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $ADDRESS;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $ADDRESS1;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $ADDRESS2;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=false)
     */
    public $EXTRA_DETAILS;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $CITY;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $STATE;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=false)
     */
    public $ZIPCODE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_COUNTRY;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $LAND_OWNERSHIP_STATUS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $GROUND_LEASE_MONTH;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $GROUND_LEASE_ENDS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $GROUND_LEASE_YEAR;

    /**
     *
     * @var string
     * @Column(type="string", length=350, nullable=false)
     */
    public $GROUND_LEASE_DETAIL;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $GROSS_BUILDING_AREA;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $RENTABLE_AREA;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $LAND_SIZE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $YEAR_BUILT;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $OUTDOOR_AREAS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $PARKING_AVAILABLE;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $PK_PARKING_LEVELS;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $LAST_PROPERTY_IMPROVEMENT;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $PARKING_SPACES_TOTAL_NO;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $FREE_PARKING;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_PROPERTY_CONDITION;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $PROPERTY_CONDITION_DESCRIPTION;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $PK_DEVELOPMENT_POTENTIAL;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $DEVELOPMENT_POTENTIALS_DESCRIPTION;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $EXISTING_PLANS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $EXISTING_PREMITS;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $DEVELOPMENT_AIR_RIGHTS;

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
     * @Column(type="string", length=300, nullable=true)
     */
    public $MEASUREMENT_STANDARD;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $STANDARDS;

    /**
     *
     * @var string
     * @Column(type="string", length=300, nullable=false)
     */
    public $FEATURED_IMAGE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ASSERT_UNDER_INSOLVENCY;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $INSOLVENCY_DETAILS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $FIELD_OPTION;

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
     * @Column(type="integer", length=1, nullable=false)
     */
    public $SHOW_ON_COMPANY_PROFILE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $MISSING_MANDATORY_FIELDS_COUNT;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $SHORT_DESCRIPTION;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $LATITUDE;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $LONGITUDE;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $COORDINATE;

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
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ALLOW_PUBLIC_FILES_DOWNLOAD;

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
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $RECORD_DATE;

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
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_DELETED;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("CSEXCHANGE");
        $this->hasMany('PK_PROPERTY_MASTER', 'CsxMediaMediaCategorySection', 'property_id', ['alias' => 'CsxMediaMediaCategorySection']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'PROPERTY_MASTER';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropertyMaster[]|PropertyMaster
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PropertyMaster
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
