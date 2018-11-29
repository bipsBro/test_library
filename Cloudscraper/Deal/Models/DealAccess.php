<?php
namespace Library\Cloudscraper\Deal\Models;
class DealAccess extends \Library\System\Model\AbstractModel
{
    /**
     * DEAL_ACCESS values 
     * @var integer
     */
    const DEAL_ACCESS_GRANTED = 1;
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_ACCESS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_MASTER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_SYNDICATION_DEAL;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $ACCESS_TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACCESS_FOR;

    /**
     *
     * @var integer
     * @Column(type="integer", length=5, nullable=false)
     */
    public $REQUIRE_NDA;

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
    public $DEAL_ACCESS_TO;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $DEAL_ACCESS_TO_USER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $DEAL_ACCESS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $VIEW_RENTROLL;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $USER_ACCESS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $NDA_STATUS;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $MAIL_SENT;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $NOTIFICATION_SENT;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $USER_AGREED;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACCESS_REQUESTED;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $DATE_OF_REQUEST;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $DATE_OF_GRANTING;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $DATE_OF_REVOKE;

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
        return 'DEAL_ACCESS';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealAccess[]|DealAccess
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealAccess
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
