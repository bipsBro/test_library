<?php
namespace Library\Cloudscraper\Account\Models;
class AccountType extends \Library\System\Model\AbstractModel
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $PK_ACCOUNT_TYPES;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $ROLE;

    /**
     *
     * @var string
     * @Column(type="string", length=10, nullable=false)
     */
    public $PREFIX;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $DISPLAY_ORDER;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $ID;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $ACTIVE;

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
        return 'DD_ACCOUNT_TYPES';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DdAccountTypes[]|AccountType
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccountType
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
