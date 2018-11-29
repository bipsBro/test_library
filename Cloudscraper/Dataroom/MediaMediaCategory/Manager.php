<?php
/**
 * Manages information about media assigned to particular category.
 */
namespace Library\Cloudscraper\Dataroom\MediaMediaCategory;

class Manager extends \Library\System\Manager\AbstractManager
{

    protected $_mediaMediaCategoryIds = [];

    /**
     * Set mediaMediaCategory Ids.
     *
     * @param array $ids
     * @return \Library\Cloudscraper\Dataroom\MediaMediaCategory\Manager
     */
    public function setMediaMediaCategoryIds(Array $ids)
    {
        $this->_mediaMediaCategoryIds = $ids;
        return $this;
    }

    /**
     * Return mediaMediaCategory Ids.
     *
     * @return array
     */
    public function getMediaMediaCategoryIds()
    {
        return $this->_mediaMediaCategoryIds;
    }

    /**
     * Returns media category by category ids.
     *
     * @param string $categoryIds
     * @return mixed
     */
    public function getMediaAssignedToCategory()
    {
        $result = $this->modelsManager->createBuilder()
            ->columns([
            "MMC.id as media_id",
            "MMC.media_category_id as media_category_id",
            "M.path as media_path",
            "M.name as media_name",
            "M.created_at as media_created_date",
            "IF(MC.name IS NULL, 'Dataroom', MC.name) as media_category_name",
            "concat(U.FIRST_NAME,' ',U.LAST_NAME) as media_created_by"
        ])
        ->addFrom("Library\Cloudscraper\Dataroom\MediaMediaCategory\Models\MediaMediaCategory", "MMC")
        ->inWhere("MMC.id", $this->getMediaMediaCategoryIds())
        ->join("\Library\Cloudscraper\Dataroom\Media\Models\Media", "MMC.media_id = M.id", "M")
        ->join("\Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory", "MMC.media_category_id = MC.id", "MC","LEFT OUTER")
        ->join("\Library\Cloudscraper\User\Models\UserMaster", "U.PK_USER_MASTER = M.created_by", "U")
        ->getQuery()
        ->execute();
        if (count($result) > 0) {
            return $result;
        }
        
        throw new ManagerException(sprintf("Cannot find the record with id(s) %s",implode(",",$this->getMediaMediaCategoryIds())));
    }
}

class ManagerException extends \Phalcon\Exception
{
}