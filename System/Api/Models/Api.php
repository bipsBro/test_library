<?php

namespace Library\System\Api\Models;

class Api extends \Phalcon\Mvc\Model
{
    use CsxApiTimestampable;

    const APIKEY_STATUS_REQUIRED_TRUE = 1;

    const APIKEY_STATUS_REQUIRED_FALSE = 0;

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
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $key;

    /**
     * @var string
     * temporary token
     */
    private $_token;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $token_hash;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $created_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $updated_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $expires_at;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $status;

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
        return 'csx_api';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxApi[]|CsxApi
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxApi
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param $token
     */
    public function setToken($token){
        $this->_token = $token;
    }

    /**
     * @return string
     */
    public function getToken(){
        return $this->_token;
    }
}

trait CsxApiTimestampable
{
    public function beforeCreate()
    {
        $this->created_at = time();
        $this->updated_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }
}
