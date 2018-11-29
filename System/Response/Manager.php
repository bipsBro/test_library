<?php
namespace Library\System\Response;
class Manager extends \Phalcon\Http\Response{
    /**
     * response status 
     * @var string
     */
    const RESPONSE_STATUS_SUCCESS_TRUE = true;
    
    const RESPONSE_STATUS_SUCCESS_FALSE = false;
    
    protected $_response = [
        'success' => self::RESPONSE_STATUS_SUCCESS_TRUE,
        'data'=> null,
        'message'=>null,
        'status_code'=>200
    ];
    
    /**
     * set response status
     * @param unknown $status
     */
    public function setResponseStatus($status = self::RESPONSE_STATUS_SUCCESS_TRUE){
        $this->_response['success'] = (boolean)$status;
    }
    
    /**
     * Set data.
     * @param unknown $data
     */
    public function setData($data = null){
        $this->_response['data'] = $data;
    }
    
    public function setRawData($data = null){
        $this->_response = $data;
    }
    
    /**
     * Set status code.
     * {@inheritDoc}
     * @see \Phalcon\Http\Response::setStatusCode()
     */
    public function setStatusCode($statusCode,$message=NULL){
        $this->_response['status_code'] = $statusCode;
        parent::setStatusCode($statusCode);
    }
    
    /**
     * Set messgae.
     * @param string $message
     */
    public function setMessage($message){
        $this->_response['message'] = $message;
    }
    
    public function send(){
        parent::setHeader("Content-Type", "application/json");
        parent::setContent(json_encode($this->_response));
        parent::send();
        exit;
    }
    
    public function getResponse(){
        return $this->_response;
    }
    
}