<?php
namespace Library\Cloudscraper\Acl\TransactionRole\Models;
use Library\System\Model\Behavior\Slug;
class TransactionRole extends \Library\System\Model\AbstractModel
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
     * @var string
     * @Column(type="string", length=256, nullable=true)
     */
    public $transaction_role;

    /**
     *
     * @var string
     * @Column(type="string", length=256, nullable=true)
     */
    public $slug;

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
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->addBehavior(new Slug());
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_transaction_roles';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxTransactionRoles[]|TransactionRole
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TransactionRole
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
    
    /**
     * Returns string for slug.
     * @return string
     */
    public function getSlug(){
        $slugString = sprintf("%s",$this->transaction_role);
        $phalconSlugLibrary = new \Phalcon\Utils\Slug();
        return $phalconSlugLibrary->generate($slugString);
    }

}
