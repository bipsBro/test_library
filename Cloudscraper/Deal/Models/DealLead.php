<?php
namespace Library\Cloudscraper\Deal\Models;
class DealLead extends  \Library\System\Model\AbstractModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_DEAL_LEAD;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

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
    public $PK_TEAM_ROLES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $DEAL_LEAD_PK_USER_MASTER;

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
        return 'DEAL_LEAD';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealLead[]|DealLead
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DealLead
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
