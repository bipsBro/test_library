<?php
namespace Library\Cloudscraper\Acl\Group\Models;

class Group extends \Phalcon\Mvc\Model
{
    const TRANSACTION_POSITIONS = Array(
        "Sellside",
        "Buyside",
        "Lender"
    );

    /**
     *
     * @var integer @Primary
     *      @Identity
     *      @Column(type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string @Column(type="string", nullable=true)
     * @enum list self::TRANSACTION_POSITIONS
     */
    public $transaction_position;

    /**
     *
     * @var integer @Column(type="integer", length=10, nullable=true)
     */
    public $transaction_role_id;

    /**
     *
     * @var integer @Column(type="integer", length=10, nullable=true)
     */
    public $system_role_id;

    /**
     *
     * @var integer @Column(type="integer", length=1, nullable=true)
     */
    public $default;

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
     *
     * @var integer @Column(type="integer", length=1, nullable=true)
     */
    public $status;

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
        return 'csx_groups';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxGroups[]|Group
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Group
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
