<?php
namespace Library\Cloudscraper\Dataroom\Media;
class Manager extends \Library\System\Manager\AbstractManager
{
    /**
     * Returns media category by category ids.
     * @param string $categoryIds
     * @return mixed
     */
    public function getMediaByIds(Array $mediaIds){
        return \Library\Cloudscraper\Dataroom\Media\Models\Media::query()
                ->inWhere("id", $mediaIds)
                ->execute();
    }
}

class ManagerException extends \Phalcon\Exception{
    
}