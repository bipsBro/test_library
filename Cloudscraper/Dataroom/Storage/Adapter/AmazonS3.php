<?php
namespace Library\Cloudscraper\Dataroom\Storage\Adapter;

use Aws\S3\Exception\S3Exception;
use Phalcon\Mvc\Model\MetaData;

class AmazonS3
{

    const ACL_PRIVATE = 'private';

    const ACL_PUBLIC_READ = 'public-read';

    const ACL_PUBLIC_READ_WRITE = 'public-read-write';

    const ACL_AUTHENTICATED_READ = 'authenticated-read';

    const STORAGE_CLASS_STANDARD = 'STANDARD';

    const STORAGE_CLASS_RRS = 'REDUCED_REDUNDANCY';

    const STORAGE_CLASS_STANDARD_IA = 'STANDARD_IA';

    /**
     *
     * @var \Aws\S3\S3Client
     */
    protected $_s3Client = null;

    /**
     *
     * @var array
     */
    protected $_config = array();

    /**
     *
     * @var string
     */
    protected $_bucket = null;

    public function init()
    {
        $this->_s3Client = new \Aws\S3\S3Client([
            "credentials" => [
                "key" => $this->getS3Key(),
                "secret" => $this->getAmazonSecret()
            ],
            "region" => $this->getS3StorageRegion(),
            "version" => $this->getS3Version()
        ]);
    }

    public function getS3Client()
    {
        return $this->_s3Client;
    }

    /**
     *
     * @var $_amazonSecret;
     */
    protected $_amazonSecret;

    public function setAmazonSecret($amazonSecret)
    {
        $this->_amazonSecret = $amazonSecret;
        return $this;
    }

    public function getAmazonSecret()
    {
        return $this->_amazonSecret;
    }

    /**
     *
     * @var $_s3StorageRegion;
     */
    protected $_s3StorageRegion;

    public function setS3StorageRegion($s3StorageRegion)
    {
        $this->_s3StorageRegion = $s3StorageRegion;
        return $this;
    }

    public function getS3StorageRegion()
    {
        return $this->_s3StorageRegion;
    }

    /**
     *
     * @var $_s3Version;
     */
    protected $_s3Version;

    public function setS3Version($s3Version)
    {
        $this->_s3Version = $s3Version;
        return $this;
    }

    public function getS3Version()
    {
        return $this->_s3Version;
    }

    /**
     * Amazon S3 storage key.
     *
     * @var unknown
     */
    protected $_s3Key;

    public function setS3Key($s3Key)
    {
        $this->_s3Key = $s3Key;
        return $this;
    }

    public function getS3Key()
    {
        return $this->_s3Key;
    }

    /**
     *
     * @var $_s3StorageClass;
     */
    protected $_s3StorageClass = self::STORAGE_CLASS_RRS;

    public function setS3StorageClass($s3StorageClass)
    {
        $this->_s3StorageClass = $s3StorageClass;
        return $this;
    }

    public function getS3StorageClass()
    {
        return $this->_s3StorageClass;
    }

    /**
     * S3 storage bucket
     *
     * @var $_s3StorageBucket;
     */
    protected $_s3StorageBucket;

    public function setS3StorageBucket($s3StorageBucket)
    {
        $this->_s3StorageBucket = $s3StorageBucket;
        return $this;
    }

    public function getS3StorageBucket()
    {
        return $this->_s3StorageBucket;
    }

    /**
     *
     * @var $_s3Acl;
     */
    protected $_s3Acl = SELF::ACL_PRIVATE;

    public function setS3Acl($s3Acl)
    {
        $this->_s3Acl = $s3Acl;
        return $this;
    }

    public function getS3Acl()
    {
        return $this->_s3Acl;
    }

    /**
     *
     * @var $_sourceFile;
     */
    protected $_sourceFile = NULL;

    public function setSourceFile($sourceFile)
    {
        $this->_sourceFile = $sourceFile;
        return $this;
    }

    public function getSourceFile()
    {
        return $this->_sourceFile;
    }

    /**
     *
     * @var $_destinationPath;
     */
    protected $_destinationPath;

    public function setDestinationPath($destinationPath)
    {
        $this->_destinationPath = $destinationPath;
        return $this;
    }

    public function getDestinationPath()
    {
        return $this->_destinationPath;
    }
    
    /**
     * Uploads file to s3 bucket.
     * 
     * @throws S3Exception
     * @return \Aws\Result
     */
    public function uploadFile()
    {
        try {
            $result = $this->_s3Client->putObject(array(
                'Bucket' => $this->getS3StorageBucket(),
                'Key' => $this->getDestinationPath() . basename($this->getSourceFile()),
                'SourceFile' => $this->getSourceFile(),
                'StorageClass' => $this->getS3StorageClass(),
                'ACL' => $this->getS3Acl()
            ));
            
            $result->formattedMetaData = $this->_formatMetaData($result);
            
            return $result;
        } catch (S3Exception $ex) {
            throw $ex;
        }
    }
    
    private function _formatMetaData($result){
        $formattedMetaData = [];
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->getSourceFile());
        finfo_close($finfo);
        
        $fileSize = filesize($this->getSourceFile());
        
        $metaData = $result->get('@metadata');
        $formattedMetaData['etag'] = $metaData['headers']['etag'];
        $formattedMetaData['date'] = $metaData['headers']['date'];
        $formattedMetaData['storage_class'] =  $metaData['headers']['x-amz-storage-class'];
        $formattedMetaData['request_id'] = $metaData['headers']['x-amz-request-id'];
        $formattedMetaData['fileSize'] = $fileSize;
        $formattedMetaData['contentType'] = $mimeType;
        return $formattedMetaData;
    }

    /**
     * Delete a file from a bucket
     *
     * @param string $key
     * @return boolean
     * @throws S3Exception
     */
    public function deleteObject()
    {
        try {
            $result = $this->_s3Client->deleteObject(array(
                'Bucket' => $this->getS3StorageBucket(),
                'Key' => $this->getDestinationPath() . basename($this->getSourceFile())
            ));
            
            return true;
        } catch (S3Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get the object identified by $key within the exploited Cloud service
     *
     * @param string $key
     *            the key on the Cloud service
     * @return mixed
     */
    public function getPresignedUrl($key)
    {
        $command = $this->_s3Client->getCommand('GetObject', array(
            'Bucket' => $this->getS3StorageBucket(),
            'Key' => $key,
            'ResponseContentType' => 'application/force-download',
            'ResponseCacheControl' => 'No-cache',
            'ResponseContentDisposition' => 'inline; filename="' . basename($key) . '"'
        ));
        
        $request = $this->_s3Client->createPresignedRequest($command, '+120 minutes');
        
        // Get the actual presigned-url
        return (string) $request->getUri();
    }

    /**
     *
     * @param String $key
     * @throws S3Exception
     * @return boolean
     */
    public function getFile($key)
    {
        try {
            $result = $this->_s3Client->getObject(array(
                'Bucket' => $this->getS3StorageBucket(),
                'Key' => $key
            ));
            
            return $result;
        } catch (S3Exception $ex) {
            throw $ex;
        }
    }
}
