<?php
namespace Library\Cloudscraper\User\Models;
class UserMaster extends \Library\System\Model\AbstractModel
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
    public $PK_USER_MASTER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_USER_TYPES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_USER_FUNCTION;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=false)
     */
    public $USER_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=true)
     */
    public $EMAIL_ID;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $PASSWORD;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $FIRST_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $LAST_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=false)
     */
    public $MOBILE;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=false)
     */
    public $PHONE;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $IMAGE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    public $WORKING_IN_FIRM_SINCE;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $CITY_COUNTRY_LIVEDIN;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $BIO;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $COMPANY_NAME;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ENTITY_TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_COUNTRY;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_CURRENCY;

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
    public $PK_OFFICE_LOCATIONS;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $DATE_FORMAT;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $TIMEZONE;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $CITY;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $CURRENT_POSITION;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $LINKEDIN;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $GOOGLE_PLUS;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $TWITTER;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $AREA_OF_EXPERTISE;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $RESPONSIBILITIES;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $HOBBIES_INTEREST;

    /**
     *
     * @var string
     * @Column(type="string", length=300, nullable=false)
     */
    public $INTRODUCED_BY_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=300, nullable=false)
     */
    public $INTRODUCED_BY_COMPANY;

    /**
     *
     * @var string
     * @Column(type="string", length=300, nullable=false)
     */
    public $INTRODUCED_BY_EMAIL;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=false)
     */
    public $MDK_USER_ID;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=false)
     */
    public $MDK_AUTHCODE;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $DEVICE_ID;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $DEVICE_ACTIVATED_ON;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=false)
     */
    public $QR_LOGIN;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $MISSING_MANDATORY_FIELDS_COUNT;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $CONSENT;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $POSSIBLY_RELATED_ACCOUNTS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_ASSIGNED;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_SEARCHABLE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $USER_IS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_EXTERNAL_INVITES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_MEX;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $QR_SCANNER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $RUN_EMAILCONTROL_CRON;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $ACTIVE;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $RECORD_DATE;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=true)
     */
    public $SECRET;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=true)
     */
    public $API;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $HIDE;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('PK_USER_MASTER', 'CsxMedia', 'created_by', ['alias' => 'CsxMedia']);
        $this->hasMany('PK_USER_MASTER', 'CsxMediaCategory', 'created_by', ['alias' => 'CsxMediaCategory']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'USER_MASTER';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserMaster[]|UserMaster
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserMaster
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
