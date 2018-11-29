<?php
namespace Library\Cloudscraper\Acl\UserGroup\Models;

class UserGroup extends \Library\System\Model\AbstractModel
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $group_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $section;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $section_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    public $created_by;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("CSEXCHANGE");
        $this->belongsTo('user_id', '\USERMASTER', 'PK_USER_MASTER', ['alias' => 'USERMASTER']);
        $this->belongsTo('group_id', '\CsxGroups', 'id', ['alias' => 'CsxGroups']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_user_groups';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxUserGroups[]|CsxUserGroups
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxUserGroups
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
