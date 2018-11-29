<?php
namespace Library\Cloudscraper\Dataroom;

use Library\Cloudscraper\Dataroom\Models\CsxMediaCategory;
use Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection;
use Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection;
use Library\Cloudscraper\Dataroom\Models\CsxMedia;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction;

class Manager extends \Library\System\Manager\AbstractManager
{
    use \Library\Cloudscraper\Dataroom\Traits\Section;

    /**
     *
     * @var mixed
     */
    protected $_storageAdapter;

    /**
     * Sets storage adapter
     *
     * @param unknown $storageAdapter
     * @return \Library\Cloudscraper\Dataroom\Manager
     */
    public function setStorageAdapter($storageAdapter)
    {
        $this->_storageAdapter = $storageAdapter;
        
        return $this;
    }

    /**
     * Return storage adapter.
     *
     * @return unknown
     */
    public function getStorageAdapter()
    {
        return $this->_storageAdapter;
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
        if (! file_exists($this->_sourceFile)) {
            throw new \Library\Cloudscraper\Dataroom\ManagerException("Invalid source file.");
        }
        
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

    public function uploadFile()
    {
        $this->getStorageAdapter()->setSourceFile($this->getSourceFile());
        $this->getStorageAdapter()->setDestinationPath($this->getDestinationPath());
        return $this->getStorageAdapter()->uploadFile();
    }

    public function folderExists()
    {
        $this->getStorageAdapter()->doesObjectExist($this->getDestinationPath());
    }
    
    public function deleteFile()
    {
        $this->getStorageAdapter()->setSourceFile($this->getSourceFile());
        $this->getStorageAdapter()->setDestinationPath($this->getDestinationPath());
        return $this->getStorageAdapter()->deleteObject();
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public function getFileDownloadUrl($key)
    {
        return $this->getStorageAdapter()->getPresignedUrl($key);
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public function getFile($key)
    {
        return $this->getStorageAdapter()->getFile($key);
    }

    /**
     *
     * @param array $postParams
     * @return unknown
     */
    public function renameMediaCategory(array $postParams)
    {
        $categoryDetail = (new CsxMediaCategory())->findFirst($postParams['category_id']);
              
        if ($categoryDetail->default == 1) {
            $this->updateMediaDetails($postParams);
            
        } else {
            return (new CsxMediaCategory())->save([
                'name' => $postParams['category'],
                'id' => $postParams['category_id'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
    }

    /**
     * Update media details after renaming category/folder name
     * @param unknown $postParams
     * @return PDOException
     */
    private function updateMediaDetails($postParams)
    {
        try {
            $newCategory = new CsxMediaCategory();
            
            $newCategory->save([
                'name' => $postParams['category'],
                'parent_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->get('SESSION_PK_USER_MASTER'),
                'default' => null
            ]);
        } catch (\PDOException $exception) {
            return $exception;
        }
        
        // update mediaCategorySection details
        $categorySections = CsxMediaCategorySection::find(
            [
                'conditions' => '(media_category_id = :media_category_id: OR parent_media_category_id = :media_category_id:) AND deal_id = :deal_id:',
                'bind' => ['media_category_id' => $postParams['category_id'], 'deal_id' => $postParams['deal_id']]
            ]);
        
        if (count($categorySections) > 0) {
            foreach ($categorySections as $categorySection) {
           
                if ($postParams['category_id'] == $categorySection->media_category_id) {
                    $categorySection->media_category_id = $newCategory->id;
                } else {
                    $categorySection->parent_media_category_id = $newCategory->id;
                }
                $categorySection->updated_at = date('Y-m-d H:i:s');
                $categorySection->created_by = $this->session->get('SESSION_PK_USER_MASTER');
                $categorySection->save();               
                
            }
        } 
        
        // update mediaMediaCategorySection
        $categoryMediaSections = (new CsxMediaMediaCategorySection())->find([
            'conditions' => 'media_category_id = :media_category_id: AND deal_id = :deal_id:',
            'bind' => ['media_category_id' => $postParams['category_id'], 'deal_id' => $postParams['deal_id']]
        ]);
        
        if (count($categoryMediaSections) > 0) {
            foreach ($categoryMediaSections as $categoryMediaSection) {
                $categoryMediaSection->media_category_id = $newCategory->id;
                $categoryMediaSection->updated_at = date('Y-m-d H:i:s');
                $categoryMediaSection->created_by = $this->session->get('SESSION_PK_USER_MASTER');
                $categoryMediaSection->save();                
            }
        }
    }
    
    
    
    public function renameMedia(array $postParams)
    {
        return (new CsxMedia())->save([
            'name' => $postParams['category'],
            'id' => $postParams['media_id'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Inserts media  to database.
     */
    public function addMedia($data){
        $transactionManager = new \Phalcon\Mvc\Model\Transaction\Manager();
        $mediaModel = new \Library\Cloudscraper\Dataroom\Media\Models\Media();
        $mediaModel->setTransaction($transactionManager->get());
    
        if ($mediaModel->save($data) === false) {
            $transactionManager->get()->rollback($mediaModel->getFormattedMessages());
        }
        
        $mediaMediaCategoryModel = new \Library\Cloudscraper\Dataroom\MediaMediaCategory\Models\MediaMediaCategory();
        $mediaMediaCategoryModel->setTransaction($transactionManager->get());
        $mediaCategoryData = [
            'media_id' => $mediaModel->id,
            'media_category_id' => $data['category_id'],
            'section' => $data['section'],
            'section_id' => $data['section_id'],
            'created_by' => $data['created_by']
        ];
        
        if($mediaMediaCategoryModel->save($mediaCategoryData) === false){
            $transactionManager->get()->rollback($mediaMediaCategoryModel->getFormattedMessages());
        }
        
        $transactionManager->get()->commit();
        
       
        $response = [
            'media' => $mediaModel->toArray(),
            'category' => $mediaMediaCategoryModel->toArray()
        ];
        
        return $response;
    }
    /**
     * 
     * @param object $s3UploadedObject
     * @param object $file
     * @param int $deal_id
     * @param int $media_category_id
     */
    public function createMedia($s3UploadedObject, $file, $dealId = null, $mediaCategoryId = null)
    {
        try {
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            
            $transaction = $manager->get();
            
            $media = new CsxMedia();
            
            $media->setTransaction($transaction);
            
            $media->name = $file->getName();
            $media->description = $s3UploadedObject->get('ObjectURL');
            $media->url = $s3UploadedObject->get('ObjectURL');
            $media->path = $this->session->get('SESSION_PK_ACCOUNT_MASTER') . '/' . $file->getName();
            $media->created_by = $this->session->get('SESSION_PK_USER_MASTER');
            $media->vendor = 's3';
            $media->status = 1;
            $media->mime = mime_content_type($this->getSourceFile());
            
            if ($media->save() === false) {
                $transaction->rollback("Can't save category");
            }
            
            $mediaCategorySection = new CsxMediaMediaCategorySection();
            
            $mediaCategorySection->setTransaction($transaction);
            
            $mediaCategorySection->media_category_id = ($mediaCategoryId == '') ? null : $mediaCategoryId;
            $mediaCategorySection->media_id = $media->id;
            $mediaCategorySection->deal_id = $dealId;
            $mediaCategorySection->status = 'enabled';
            $mediaCategorySection->created_by = $this->session->get('SESSION_PK_USER_MASTER');
            
            if ($mediaCategorySection->save() === false) {
                $transaction->rollback("Can't save media category section");
            }
            
            $transaction->commit();
        } catch (Transaction\Failed $e) {
            echo "Failed, reason: ", $e->getMessage();
        }
    }

    /**
     *
     * @param array $postParams
     */
    public function createMediaCategory(array $postParams)
    {
        try {
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            
            $transaction = $manager->get();
            
            $category = new CsxMediaCategory();
            
            $category->setTransaction($transaction);
            
            $category->name = $postParams['category'];
            $category->parent_id = $postParams['parent_category_id'];
            $category->status = 1;
            $category->section = $postParams['section'];
            $category->created_by = $this->session->get('SESSION_PK_USER_MASTER');
            $category->created_at = $category->updated_at = date('Y-m-d H:i:s');
            $category->default = 0;
            
            if ($category->save() === false) {
                $transaction->rollback("Can't save category");
            }
            
            $mediaCategorySection = new CsxMediaCategorySection();
            
            $mediaCategorySection->setTransaction($transaction);
            
            $mediaCategorySection->media_category_id = $category->id;
            $mediaCategorySection->parent_media_category_id = $postParams['parent_category_id'];
            $mediaCategorySection->deal_id = $postParams['deal_id'];
            $mediaCategorySection->status = 1;
            $mediaCategorySection->created_by = $this->session->get('SESSION_PK_USER_MASTER');
            $mediaCategorySection->created_at = $mediaCategorySection->updated_at = date('Y-m-d H:i:s');
            
            if ($mediaCategorySection->save() === false) {
                $transaction->rollback("Can't save media category section");
            }
            
            return $transaction->commit();
        } catch (Transaction\Failed $e) {
            echo "Failed, reason: ", $e->getMessage();
        }
    }

    public function addMediaCategory(Array $data)
    {
        $mediaCategoryModel = new \Library\Cloudscraper\Dataroom\Models\CsxMediaCategory();
        $mediaCategoryModel->setModelProperties($mediaCategoryModel, $data);
        if ($mediaCategoryModel->save() === false) {
            throw new ManagerException($mediaCategoryModel->getFormattedMessages());
        }
        
        return $mediaCategoryModel;
    }

    /**
     * Creates media category section
     *
     * @param \ArrayObject $data
     
    public function addMediaCategorySection(Array $data)
    {
        \Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection::find([
            "conditions" => "media_category_id = :media_category_id: and parent_media_category = :parent_media_category: and $this->getSectionKey() = :section_value: ",
            "bind" => [
                'media_category_id' => $data['media_category_id'],
                'parent_media_category' => $data['parent_media_category_id'],
            ]
        ]);
        
        $mediaCategorySectionModel = new \Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection();
        $mediaCategorySectionModel->setModelProperties($mediaCategorySectionModel, $data);
        
        if ($mediaCategorySectionModel->save() === false) {
            throw new ManagerException($mediaCategorySectionModel->getFormattedMessages());
        }
        
        return $mediaCategorySectionModel;
    }
    */
    /**
     * Returns Media category by slug.
     * @param unknown $slug
     * @return \Library\Cloudscraper\Dataroom\Models\CsxMediaCategory|boolean
     */
    public function getMediaCategoryBySlug($slug){
        
        $mediaCategoryModel = \Library\Cloudscraper\Dataroom\Models\CsxMediaCategory::findFirst([
            'conditions' => "slug = :slug:",
            'bind' => [
                'slug' => $slug
            ]
        ]);
        
        if($mediaCategoryModel instanceof \Library\Cloudscraper\Dataroom\Models\CsxMediaCategory){
            return $mediaCategoryModel;
        }
        
        return false;
    }

    /**
     * 
     * @param array $categories
     * @return mixed
     */
    public function getMediaCategories(array $categories)
    {
        return $this->modelsManager->createBuilder()
            ->from('Library\Cloudscraper\Dataroom\Models\CsxMediaCategory')
            ->inWhere("id", $categories)
            ->getQuery()
            ->execute();
    }

    /**
     *
     * @param
     * json String $jsonData
     * @return number[][]|mixed[][] 
     * 
     */
     private function getCategoryFormattedData($jsonData) 
     {
          $categoryFormatter = new CategoryFormatter($jsonData);
          return $categoryFormatter->getFormattedData();
     }
     
    
    /**
     * category importer
     *
     * @param json string $jsonData
     */            
    public function importPredefinedCategoryStructure($jsonData, $sectionType)
    {
        $formattedData = $this->getCategoryFormattedData($jsonData);
     
        // Import media categories in database
        $importer = new CategoryImporter($formattedData, $sectionType);
        $importer->import();
    }
     
    
    

    /**
     * This function is invoked when event is fired \Library\Cloudscraper\Activity\Deal:afterAssetCreated
     *
     * @param unknown $event
     * @param unknown $asset
     */
    public function afterAssetCreated($event, $asset)
    {
        if (is_object($deal)) {
            $this->setAssetId($deal->assetId);
            $this->setDefaultMediaCategorySection();
        }
    }
    
    /**
     * Checks if a section has default categories added in the database.
     */
    public function setDefaultMediaCategorySection()
    {
        // Check if CsxMediaCategorySection has default categories for defined section key.
       
        
        $result = CsxMediaCategorySection::query()->columns('id')
            ->where("{$this->getSectionKey()} = {$this->getSectionValue()}")
            ->execute();
        if (count($result) == 0) {
            // Get default category for section type.
            $result = CsxMediacategory::query()->columns('name,id,parent_id')
                ->where("section = '{$this->getSectionType()}'")
                ->execute();
            if (count($result) > 0) {
                $sectionKey = $this->getSectionKey();
                $sectionValue = $this->getSectionValue();
                foreach ($result as $row) {
                  
                    try {
                        // Insert into media category section table.
                        $mediaCategorySectionModel = new CsxMediaCategorySection();
                        $mediaCategorySectionModel->media_category_id = $row->id;
                        $mediaCategorySectionModel->parent_media_category_id = $row->parent_id;
                        $mediaCategorySectionModel->$sectionKey = $sectionValue;
                        $mediaCategorySectionModel->save();
                    } catch (\PDOException $exception) {
                        echo sprintf("\nCannot insert default category for %s=%s\n", $sectionKey, $sectionValue);
                    }
                    
                }
            }
        }
    }
    
    /*
     * Creates archieve of media and categories.
     */
    public function createMediaAndCategoriesArchieve($parentId = 0){
        // Get all the categories and sub categories defined for a section.
        $mediaCategorySectionModels = CsxMediacategorySection::query()->columns([
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.name",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.id",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection.parent_media_category_id as parent_id"
        ])
        ->leftJoin("Library\Cloudscraper\Dataroom\Models\CsxMediaCategory", "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.id = \Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection.media_category_id")
        ->where("{$this->getSectionKey()} = :section_value:")
        ->bind(['section_value' => $this->getSectionValue()])
        ->execute();
        
        
        foreach ($mediaCategorySectionModels as $mediaCategorySectionModel) {
            $data[] = $mediaCategorySectionModel->toArray();
        }
        
        $categories = array(
            0 => array(
                'subcategories' => array()
            )
        );
        
        foreach($data as $datum){
            if(empty($datum['parent_id'])){
                $datum['parent_id'] = 0;
            }
            
            $categories[$datum['id']]= [
                'id' => $datum['id'],
                'parent_id' => $datum['parent_id'],
                'name' => $datum['name'],
                'subcategories' => []
            ];
        }
        
        foreach ($categories as &$category) {
            if(array_key_exists('parent_id',$category)){
                $categories[$category['parent_id']]['subcategories'][] = &$category;
            }
        }
        
        if($parentId == 0){
            $categories = $categories[$parentId]['subcategories'];
        }else{
            $_categories = [];
            $_categories[$parentId] = $categories[$parentId];
            $categories = $_categories;
            unset($_categories);
        }
        ksort($categories);
        $categories = $this->getNestedDataroomCategories($categories);
        
        // Get all the media for section
        $mediaModels = CsxMediaMediaCategorySection::query()->columns([
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.name",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.id",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.path",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.url",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection.media_category_id as category_id"
        ])
        ->leftJoin("Library\Cloudscraper\Dataroom\Models\CsxMedia", "\Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection.media_id = Library\Cloudscraper\Dataroom\Models\CsxMedia.id")
        ->where("{$this->getSectionKey()} = :section_value:")
        ->bind(['section_value' => $this->getSectionValue()])
        ->execute();
        
        $media = [];
        $mediaAndCategories = [];
        foreach($mediaModels as $mediaModel){
            $mediaData = $mediaModel->toArray();
            $media[$mediaModel->category_id]['files'][] = $mediaData;
        } 
        
        $mediaAndCategories = array_replace_recursive($categories, $media);
        
        echo "<pre>";
        print_r(($mediaAndCategories));
        echo "</pre>";
        exit;
      
        
    }
    
    /**
     * Returns categories and subcategories in tree structure .
     * Note that this function uses recursive.
     * @param unknown $data
     * @param array $nestedData
     * @param number $level
     * @param array $folders
     * @return unknown|array[]|unknown[]|unknown[][]|mixed[]
     */
    public function getNestedDataroomCategories($categories,&$nestedCategories=[],$level=0,$categoryStructure=[]){
        if($categories == NULL){
            return $nestedCategories;
        }
        
        $level++;
        
        while($datum = array_shift($categories)){
            if(is_array($datum)){
                if($level == 1){
                    $categoryStructure = [];
                }
                
                if($datum['parent_id'] != 0){
                    if(array_key_exists($datum['parent_id'], $nestedCategories)){
                        $categoryStructure[$datum['parent_id']] = $nestedCategories[$datum['parent_id']]['name'];
                    }
                }
                
                $nestedCategories[$datum['id']] = [
                    'id'=>$datum['id'],
                    'name' => $datum['name'],
                    'parent_id' => $datum['parent_id'],
                    'category_structure' => array_replace_recursive($categoryStructure,[$datum['id']=>$datum['name']]),
                    'level' => $level,
                    'files' => []
                ];
            }
            
            if(count($datum['subcategories'])>0){
                $this->getNestedDataroomCategories($datum['subcategories'],$nestedCategories,$level,$categoryStructure);
            }
        }
        
        return $nestedCategories;
    }
    
    
    /**
     *
     * @param string $sectionType
     * @return []
     */
    private function getSectionQueryParamsBySectionType($sectionType = null)
    {
        switch ($sectionType) {
            case 'DEAL':
                $sectionWhere = 'MCS.deal_id = :deal_id';
                $fileSectionWhere = 'MMCS.deal_id = :deal_id';
                $bindParam = 'deal_id';
                break;
            case 'ASSET':
                $sectionWhere = 'MCS.asset_id = :asset_id';
                $fileSectionWhere = 'MMCS.asset_id = :asset_id';
                $bindParam = 'asset_id';
                break;
            default:
                $sectionWhere = 'MCS.deal_id = :deal_id';
                $fileSectionWhere = 'MMCS.deal_id = :deal_id';
                $bindParam = 'deal_id';
                break;
        }
        
        return ['sectionWhere' => $sectionWhere, 'fileWhere' => $fileSectionWhere, 'bindParam' => $bindParam];
    }
    
    /**
     *
     * @param string $sectionType
     * @return []
     */
    private function getCategoryQueryParamsBySectionType($sectionType = null)
    {
        switch ($sectionType) {
            case 'DEAL':
                //$categoryWhere = 'MC.parent_id = :parent_id';
                $categoryWhere = 'MCS.parent_media_category_id = :parent_id';
                $fileWhere = 'MMCS.media_category_id = :parent_id';
                $bindParam = 'parent_id';
                break;
            case 'ASSET':
                $categoryWhere = 'MCS.asset_id = :asset_id';
                $fileWhere = 'MMCS.asset_id = :asset_id';
                $bindParam = 'asset_id';
                break;
            default:
                $categoryWhere = 'MCS.deal_id = :deal_id';
                $fileWhere = 'MMCS.deal_id = :deal_id';
                $bindParam = 'deal_id';
                break;
        }
        
        return ['categoryWhere' => $categoryWhere, 'fileWhere' => $fileWhere, 'bindParam' => $bindParam];
    }
    
    /**
     * 
     * @param array $postParams
     * @return array
     */
    public function buildQueryParamsForCategoryAndFilesBySectionType(array $postParams)
    {
        $queryParams = $this->getSectionQueryParamsBySectionType($this->getSectionType());
        $bindParams = [];
        
        $finalCategoryWhere = $queryParams['sectionWhere'];
        $finalFileWhere = $queryParams['fileWhere'];
        $bindParams[$queryParams['bindParam']] = $this->getSectionValue();
        $groupBy = $orderBy = $limit = $offset = '';
        
        // complex logic to accomodate conditions when parent category id is not null
        if (!is_null($postParams['parentCategoryId']) && $postParams['parentCategoryId'] !== '') {
            $categoryQueryParams = $this->getCategoryQueryParamsBySectionType($this->getSectionType());
            $finalCategoryWhere .= ' AND ' .$categoryQueryParams['categoryWhere'];
            
            $finalFileWhere .= ' AND ' .$categoryQueryParams['fileWhere'];
            $bindParams[$categoryQueryParams['bindParam']] = $postParams['parentCategoryId'];
            
            $finalFileWhere  .= " AND media_category_id = {$postParams['parentCategoryId']}";
            $fileCategoryColumn = "MMCS.media_category_id";
        } else {
            if ($postParams['displayAll'] === false) {
                $finalCategoryWhere .= " AND MC.parent_id IS NULL";
                $finalFileWhere  .= " AND MMCS.media_category_id is NULL";               
            }
            $fileCategoryColumn =  "  NULL as media_category_id";
        }
        
        return [
            'categoryWhere' => $finalCategoryWhere , 
            'fileWhere' => $finalFileWhere, 
            'bindParams' => $bindParams,
            'fileSelectColumn' => $fileCategoryColumn
        ];
    }
    
    /**
     * Checks if a section has default categories added in the database.
     
    public function setDefaultMediaCategorySection()
    {
        // Check if CsxMediaCategorySection has default categories for defined section key.
        $result = CsxMediaCategorySection::query()->columns('id')
        ->where("{$this->getSectionKey()} = {$this->getSectionValue()}")
        ->execute();
        if (count($result) == 0) {
            // Get default category for section type.
            $result = CsxMediacategory::query()->columns('name,id,parent_id')
            ->where("section = '{$this->getSectionType()}'")
            ->execute();
            if (count($result) > 0) {
                $sectionKey = $this->getSectionKey();
                $sectionValue = $this->getSectionValue();
                foreach ($result as $row) {
                    try {
                        // Insert into media category section table.
                        $mediaCategorySectionModel = new CsxMediaCategorySection();
                        $mediaCategorySectionModel->media_category_id = $row->id;
                        $mediaCategorySectionModel->parent_media_category_id = $row->parent_id;
                        $mediaCategorySectionModel->$sectionKey = $sectionValue;
                        $mediaCategorySectionModel->save();
                    } catch (\PDOException $exception) {
                        echo sprintf("\nCannot insert default category for %s=%s\n", $sectionKey, $sectionValue);
                    }
                }
            }
        }
    }*/
    
    /**
     * function name: Get Nested Media Categories (media categories and media sub categories in) Tree (Structure)
     * Returns media categories and sub categories in a tree structure.
     * Media and the media categories is filtered based on the parent id passed in params.
     * @param number $parentId
     * @return Array
     */
    public function getNestedMediaCategoriesTree($parentId = 0){
        #Get all the categories and sub categories defined for a section.
        $mediaCategorySectionModels = CsxMediacategorySection::query()->columns([
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.name",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.id as category_id",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection.id as section_id",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.updated_at",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection.parent_media_category_id as parent_id"
        ])->leftJoin("Library\Cloudscraper\Dataroom\Models\CsxMediaCategory", "\Library\Cloudscraper\Dataroom\Models\CsxMediaCategory.id = \Library\Cloudscraper\Dataroom\Models\CsxMediaCategorySection.media_category_id")
        ->where("{$this->getSectionKey()} = :section_value:")
        ->bind([
            'section_value' => $this->getSectionValue()
        ])->execute();
        
       
        
        // Initialize the nested categories array.
        $categories = array(
            0 => array(
                'children' => array()
            )
        );
        
        // Loop through medica category section models and get the data in array format.
        foreach ($mediaCategorySectionModels as $mediaCategorySectionModel) {
            $data = $mediaCategorySectionModel->toArray();
            if (empty($data['parent_id'])) {
                $data['parent_id'] = 0;
            }
            
            $categories[$data['category_id']][] = [
                'id' => $data['category_id'],
                'parent_id' => $data['parent_id'],
                'name' => $data['name'],
                'children' => [],
                'updated_at' => $data['updated_at']
            ];
        }
        
        echo "<pre>";
            print_r($categories);
        echo "</pre>";
        exit;
        
        foreach ($categories as &$category) {
            if (array_key_exists('parent_id', $category)) {
                $categories[$category['parent_id']]['children'][] = &$category;
            }
        }
        
        if ($parentId == 0) {
            $categories = $categories[$parentId]['children'];
           
        } else {
            $_categories = [];
            $_categories[$parentId] = $categories[$parentId];
            $categories = $_categories;
            unset($_categories);
        }
        
        ksort($categories);
       echo "<pre>";
           print_r($categories);
       echo "</pre>";
       exit;
       
        return $categories;
    }
    
    /*
     * @fncn-def: Get nested media categories and media details in a list. Flattens the tree structure into
     * one dimenstional array but the list contains the nested media categories as well. If you dont want nested categories in a list
     * Use getFirstLevelMediaCategoriesAndMediaList. It contains first level children of the parent id.
     * Returns media and sub categories in a list.
     * The list is flattened. Each item in a list contains category structure
     * and the media in each category
     */
    public function getNestedMediaCategoriesAndMediaList($parentId=0){
        $mediaAndCategoriesTree  = $this->getNestedMediaCategoriesTree($parentId);
        echo "<pre> die here";
            print_r($mediaAndCategoriesTree);
        echo "</pre>";
        exit;
        $mediaAndMediaCategoriesList =  \Library\System\Helpers\ArrayHelper::traverseTree($mediaAndCategoriesTree,[$this,'mediaAndMediaCategoriesListFormater']);
        
        // Get all the media for section
        $mediaModels = CsxMediaMediaCategorySection::query()->columns([
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.name",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.id",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.path",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.url",
            "\Library\Cloudscraper\Dataroom\Models\CsxMedia.updated_at",
            "\Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection.media_category_id as category_id"
        ])
        ->leftJoin("Library\Cloudscraper\Dataroom\Models\CsxMedia", "\Library\Cloudscraper\Dataroom\Models\CsxMediaMediaCategorySection.media_id = Library\Cloudscraper\Dataroom\Models\CsxMedia.id")
        ->where("{$this->getSectionKey()} = :section_value:")
        ->bind(['section_value' => $this->getSectionValue()])
        ->execute();
        
        $media = [];
        
        // This is a root folder.
        $mediaAndMediaCategoriesList[0] = [
            'media' => [],
            'parent_id' => 0,
            'category_structure' => [],
            'updated_at' => ''
        ];
        
        $mediaAndMediaCategories = [];
        
        foreach ($mediaModels as $mediaModel) {
            // If the media doesnt have parent category id display it as a child of root folder.
            $mediaModel->category_id = empty($mediaModel->category_id) ? 0 : $mediaModel->category_id;
            $mediaData = $mediaModel->toArray();
            
            if(array_key_exists($mediaModel->category_id, $mediaAndMediaCategoriesList)){
                $mediaAndMediaCategoriesList[$mediaModel->category_id]['media'][] = $mediaData;
            }
            
        }
        return  $mediaAndMediaCategoriesList;
    }
    
    /*
     * @fncn-def: get first level media categories and media in a list, of a parent category id mentioned in the parameter.
     *
     */
    public function getFirstLevelMediaCategoriesAndMediaList($parentId = 0){
     
        $nestedMediaCategoriesAndMediaList =  $this->getNestedMediaCategoriesAndMediaList();
       
        $firstLevelMediaCategoriesAndMediaList= [];
        if(count($nestedMediaCategoriesAndMediaList) > 0){
            foreach($nestedMediaCategoriesAndMediaList as $mediaCategoriesAndMediaItem){
                if($mediaCategoriesAndMediaItem['parent_id'] == 0){
                    $mediaCategoryStructure = $mediaCategoriesAndMediaItem['category_structure'];
                    if(count($mediaCategoryStructure) > 0){
                        foreach($mediaCategoryStructure as $mediaCategoryId => $mediaCategoryName){
                            $media = $nestedMediaCategoriesAndMediaList[$mediaCategoryId]['media'];
                            $nestedMediaCategoriesAndMediaList[$mediaCategoriesAndMediaItem['id']]['media'][] = $nestedMediaCategoriesAndMediaList[$mediaCategoryId]['media'];
                        }
                    }
                    $firstLevelMediaCategoriesAndMediaList[] = $mediaCategoriesAndMediaItem;
                }
            }
        }
        
        return $firstLevelMediaCategoriesAndMediaList;
    }
    
    /**
     * This is the callback functio for getNestedMediaCategoriesAndMediaList
     * @param unknown $leaf
     * @param unknown $list
     * @param unknown $level
     * @param array $categoryStructure
     * @return unknown
     */
    public function mediaAndMediaCategoriesListFormater($leaf,$list,$level,&$categoryStructure=[])
    {
        if($level == 1){
            $categoryStructure = [];
        }
        
        if ($leaf['parent_id'] != 0) {
            if (array_key_exists($leaf['parent_id'], $list)) {
                $categoryStructure[$leaf['parent_id']] = $list[$leaf['parent_id']]['name'];
            }
        }
        
        $leaf['category_structure'] = array_replace_recursive($categoryStructure, [
            $leaf['id'] => $leaf['name']
        ]);
        
        $leaf['media'] = [];
        
        unset($leaf['children']);
        return $leaf;
    }
}

class ManagerException extends \Phalcon\Exception
{
}
