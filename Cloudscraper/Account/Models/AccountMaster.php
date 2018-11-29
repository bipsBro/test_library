<?php
namespace Library\Cloudscraper\Account\Models;
class AccountMaster extends \Library\System\Model\AbstractModel
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_TYPES;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $CS_TICKER_ID;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $COMPANY_NAME;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $IS_TESTACCOUNT;

    /**
     *
     * @var integer
     * @Column(type="integer", length=2, nullable=false)
     */
    public $ADMIN_IGNORE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ORPHAN;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $COMPANY_CORPORATE_ID;

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
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    public $TIMEZONE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ENTITY_TYPE;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $ORG_BACKGROUND;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_COMPANY_TYPE;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $STOCK_ID;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    public $YEAR_ESTABLISHED;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_HOLDING_CO_RELATIONSHIP;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $HOLDING_CO_NAME;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $PRIMARY_PHONE;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $PRIMARY_FAX;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $COMPANY_EMAIL;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    public $COMPANY_WEBSITE;

    /**
     *
     * @var string
     * @Column(type="string", length=11, nullable=false)
     */
    public $NO_OF_FULLTIME_EMPLOYEES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $FINANCING_THIRDPARTY_DEALS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $MAILSENT_FINANCING_THIRDPARTY_DEALS;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $COMPANY_LOGO;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $COMPANY_LOGO_THUMB;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $MISSING_MANDATORY_FIELDS_COUNT;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $USER_FOLDER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $MARKET_DEAL_BULK_UPLOAD;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACCOUNT_IS;

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
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ACCOUNT_MASTER';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccountMaster[]|AccountMaster
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccountMaster
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
