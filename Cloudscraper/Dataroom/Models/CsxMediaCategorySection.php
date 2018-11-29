<?php
namespace Library\Cloudscraper\Dataroom\Models;

class CsxMediaCategorySection extends \Library\System\Model\AbstractModel
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
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $media_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $media_category_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $section;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $section_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
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
        $this->setSchema("CSEXCHANGE");
        $this->belongsTo('media_id', '\Library\Cloudscraper\Media\Models\Media', 'id', ['alias' => 'Media']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'csx_mediaCategory_section';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediacategorySection[]|CsxMediaCategorySection
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CsxMediaCategorySection
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
   
    /**
     * 
     * @param array $conditions
     * @return boolean|unknown
     */
    public function getTreeStructure(array $conditions) 
    {
        if (!isset($conditions['categoryWhere']) && !isset($conditions['fileWhere'])) {
            return false;
        }
        
        $db = \Phalcon\DI::getDefault()->get('db');
        
        $groupBy = $orderBy = $limit = $offset = $searchQuery = '';
      
        $stmt = $db->prepare($this->getQueryForTreeStructure($conditions));
        
        $stmt->execute($conditions['bindParams']);
        
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @param array $conditions
     * @return boolean|unknown
     */
    public function getMediaCategoriesWithFiles(array $conditions)
    {
        if (!isset($conditions['categoryWhere']) && !isset($conditions['fileWhere'])) {
            return false;
        }
        
        $db = \Phalcon\DI::getDefault()->get('db');
        
        $groupBy = $orderBy = $limit = $offset = $searchQuery = '';
        
        if (isset($conditions['searchText']) && $conditions['searchText'] !== '') {
            $conditions['fileWhere'] .= " AND M.name LIKE  '%{$conditions['searchText']}%'";
            $conditions['categoryWhere'] .= " AND MC.name LIKE '%{$conditions['searchText']}%'";
        }
        
        if (isset($conditions['order'])) {
            $orderBy .= " ORDER BY ". $conditions['order'];
        } else {
            $orderBy .= " ORDER BY category_name ASC , media_name ASC ";
        }
        
        if (isset($conditions['limit']) && !is_null($conditions['limit'])) {
            $limit = " LIMIT ".$conditions['limit'];
        }
        
        if (isset($conditions['offset']) && !is_null($conditions['offset'])) {
            $offset = " OFFSET ". $conditions['offset'];
        }
        
        $stmt = $db->prepare($this->getQueryForCategoriesWithFiles($conditions). $orderBy . $limit . $offset);

        $stmt->execute($conditions['bindParams']);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @param array $conditions
     * @return boolean|string
     */
    private function getQueryForCategoriesWithFiles(array $conditions)
    {
        if (!isset($conditions['categoryWhere']) && !isset($conditions['fileWhere'])) {
            return false;
        }
        
        $query = "SELECT MCS.media_category_id, NULL as path, NULL as url, MC.parent_id AS parent_category_id,
                MCS.updated_at, MC.name AS category_name,
                NULL AS media_id, NULL AS media_name
                FROM CSEXCHANGE.csx_mediaCategory_section MCS
                JOIN csx_mediaCategory AS MC ON MC.id = MCS.media_category_id
                WHERE {$conditions['categoryWhere']} ".
                " UNION ".
                "SELECT {$conditions['fileSelectColumn']}, M.path, M.url, NULL AS parent_category_id, M.updated_at,
                NULL AS category_name, M.id AS media_id, M.name AS media_name
                FROM csx_media_mediaCategory_section MMCS
                LEFT JOIN csx_media M ON M.id = MMCS.media_id
                WHERE {$conditions['fileWhere']} ";
        
        return $query;
    }
    
    /**
     * 
     * @param array $conditions
     * @return boolean|string
     */
    private function getQueryForTreeStructure(array $conditions)
    {
        if (!isset($conditions['categoryWhere']) && !isset($conditions['fileWhere'])) {
            return false;
        }
        
        $query = "SELECT CASE WHEN MC.parent_id IS null THEN '#' ELSE MC.parent_id END as parent, 
                     MC.name AS text, MC.id as id, 'folder' as type, NULL as filepath, NULL as mime
                FROM CSEXCHANGE.csx_mediaCategory_section MCS
                JOIN csx_mediaCategory AS MC ON MC.id = MCS.media_category_id
                WHERE {$conditions['categoryWhere']} ".
                " UNION ".
                "SELECT CASE WHEN MMCS.media_category_id IS null THEN '#' ELSE MMCS.media_category_id END as parent,
                     M.name AS text, MMCS.media_id AS id,'file' as type, M.path as filepath, M.mime
                FROM csx_media_mediaCategory_section MMCS
                LEFT JOIN csx_media M ON M.id = MMCS.media_id
                WHERE {$conditions['fileWhere']} ";
        
        return $query;
    }
    
    /**
     * 
     * @param array $conditions
     * @return boolean|int
     */
    public function getMediaCategoriesWithFilesTotalCount(array $conditions)
    {
        if (!isset($conditions['categoryWhere']) && !isset($conditions['fileWhere'])) {
            return false;
        }
        
        $db = \Phalcon\DI::getDefault()->get('db');
               
        $stmt = $db->prepare("SELECT COUNT(*) FROM ({$this->getQueryForCategoriesWithFiles($conditions)}) AS count");

        $stmt->execute($conditions['bindParams']);
        
        return $stmt->fetchColumn();
    }
    

}
