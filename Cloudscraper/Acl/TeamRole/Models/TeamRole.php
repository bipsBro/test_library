<?php
namespace Library\Cloudscraper\Acl\TeamRole\Models;
class TeamRole extends \Library\System\Model\AbstractModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_TEAM_ROLES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_MASTER;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $TEAM_ROLES;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $TYPE;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    public $DISPLAY_ORDER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACTIVE;

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
        return 'DD_TEAM_ROLES';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DdTeamRoles[]|TeamRole
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TeamRole
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
