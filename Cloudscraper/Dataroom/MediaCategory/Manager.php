<?php
namespace Library\Cloudscraper\Dataroom\MediaCategory;

class Manager extends \Library\System\Manager\AbstractManager
{
    use \Library\Cloudscraper\Dataroom\Traits\Section;

    protected $_parentMediaCategoryId = NULL;

    /**
     * Set parent media category Id.
     *
     * @param unknown $parentMediaCategoryId
     * @return \Library\Cloudscraper\Dataroom\MediaCategory\Manager
     */
    public function setParentMediaCategoryId($parentMediaCategoryId)
    {
        $this->_parentMediaCategoryId = $parentMediaCategoryId;
        return $this;
    }

    /**
     * Returns parent media category Id.
     *
     * @return number|unknown
     */
    public function getParentMediaCaetgoryId()
    {
        return $this->_parentMediaCategoryId;
    }

    /**
     * Get all the categories and subcategories defined for a particular section like DEAL, ASSET
     *
     * @param boolean $nestedStructure
     * @return array[][]|unknown[][]|number[][]|array|unknown|number
     */
    public function getDefaultCategories($nestedStructure = true)
    {
        // Select all categories assigned to a section.
        $mediaCategoryModels = \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::query()->where("section= :section: and section_id =0")
            ->orderBy("id asc")
            ->bind([
            'section' => $this->getSection()
        ])
            ->execute();

        // Initialize the nested categories array.
        $defaultCategories = array(
            0 => array(
                'children' => array()
            )
        );

        // Loop through medica category section models and get the data in array format.
        foreach ($mediaCategoryModels as $mediaCategoryModel) {
            $data = $mediaCategoryModel->toArray();
            if (empty($data['parent_id'])) {
                $data['parent_id'] = 0;
            }

            $data['children'] = "";

            $defaultCategories[$data['id']] = $data;
        }

        if ($nestedStructure == false || count($defaultCategories) == 0) {
            return $defaultCategories;
        }

        foreach ($defaultCategories as &$category) {
            if (array_key_exists('parent_id', $category)) {
                $defaultCategories[$category['parent_id']]['children'][] = &$category;
            }
        }

        return $defaultCategories[0]['children'];
    }

    /**
     * Gets default categories and adds them for section (DEAL,ASSET,SYNDICATION_DEAL)
     *
     * @required section_id
     * @requred section
     */
    public function addDefaultCategories($defaultCategories, $newCategoriesDetail = [], &$sectionCategories = [])
    {
        if ($defaultCategories == NULL) {
            return $sectionCategories;
        }

        while ($defaultCategory = array_shift($defaultCategories)) {
            // Check if this category exists for a section
            $mediaCategoryModel = \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::findFirst([
                'conditions' => "slug = :slug: and section = :section: and section_id = :section_id:",
                'bind' => [
                    'slug' => $defaultCategory['slug'],
                    'section' => $this->getSection(),
                    'section_id' => $this->getSectionId()
                ]
            ]);

            if ($mediaCategoryModel instanceof \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory) {
                $sectionCategories[$defaultCategory['id']] = [
                    "new_category_id" => $mediaCategoryModel->id
                ];
            } else {
                $newCategory = $defaultCategory;
                unset($newCategory['id']);
                unset($newCategory['children']);

                $newCategory['default'] = 0;
                $newCategory['section_id'] = $this->getSectionId();

                if ($newCategory['parent_id'] > 0) {
                    $newCategory['parent_id'] = $sectionCategories[$newCategory['parent_id']]['new_category_id'];
                }

                $newCategory = array_merge($newCategory, $newCategoriesDetail);

                $mediaCategoryModel = new \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory();

                try {
                    if ($mediaCategoryModel->save($newCategory) != false) {
                        $sectionCategories[$defaultCategory['id']]['new_category_id'] = $mediaCategoryModel->id;
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }

            if (isset($defaultCategory['children'])) {
                if (count($defaultCategory['children']) > 0) {
                    $this->addDefaultCategories($defaultCategory['children'], $newCategoriesDetail, $sectionCategories);
                }
            }
        }

        return $sectionCategories;
    }

    /**
     * Returns nested media categories based on the section id set.
     *
     * @return array[]|mixed[]|boolean
     */
    public function getMediaCategoriesAndMediaBySectionId()
    {
        $mediaCategoriesAndMedia = [];

        // Get all the media categories for section.
        $mediaCategoryModels = \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::query()
            ->where("\Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory.section = :section: and
                    \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory.section_id = :section_id:")
            ->bind([
            'section' => $this->getSection(),
            'section_id' => $this->getSectionId()
        ])->execute();

      
        // Get all the media for section .
        $mediaMediacategoryModels = $this->modelsManager->createBuilder()
            ->addFrom("Library\Cloudscraper\Dataroom\MediaMediaCategory\Models\MediaMediaCategory","MMC")
            ->columns([
                "media.id",
                "media.name",
                "media.mime",
                "media.metadata",
                "media_category_id",
                "media.created_at",
                "media.updated_at",
                "media.path",
                "MMC.id as media_mediaCategory_id"
            ])
            ->leftJoin("\Library\Cloudscraper\Dataroom\Media\Models\Media", NULL, 'media')
            ->where("section = :section: and section_id = :section_id:")
            ->getQuery()
            ->execute([
                'section' => $this->getSection(),
                'section_id' => $this->getSectionId()
            ]);

        $mediaGroupedByCategories = [];
        if (count($mediaMediacategoryModels) > 0) {
            foreach ($mediaMediacategoryModels as $mediaMediacategoryModel) {
                $mediaGroupedByCategories[$mediaMediacategoryModel->media_category_id][$mediaMediacategoryModel->id] = $mediaMediacategoryModel->toArray();
            }
            unset($mediaMediacategoryModels);
        }

        if (count($mediaCategoryModels) > 0) {
            $mediaCategories = $mediaCategoryModels->toArray();
            $mediaCategories = \Library\System\Helpers\ArrayHelper::groupArrayByPrimaryKey($mediaCategories, "id");
            $mediaCategories[0] = [
                'id' => 0,
                'name' => '',
                'parent_id' => NULL,
                'media' => array(),
                'children' => array(),
                'categories' => array()
            ];

            foreach ($mediaCategories as $index => $mediaCategory) {
                $mediaCategory['media'] = [];
                $mediaCategory['children'] = [];

                if (isset($mediaGroupedByCategories[$mediaCategory['id']])) {
                    $mediaCategory['media'] = $mediaGroupedByCategories[$mediaCategory['id']];
                }

                $mediaCategories[$index] = $mediaCategory;
            }

            return $mediaCategories;
        }
    }

    /**
     * Returns child categories and media of a parent media category id in a tree structure.
     *
     * @filter parent media category id.
     * @param unknown $mediaCategoriesAndMedia
     * @return unknown
     */
    public function getChildMediaCategoriesAndMedia(Array $mediaCategoriesAndMedia)
    {
        if (count($mediaCategoriesAndMedia) == 0) {
            return [];
        }

        foreach ($mediaCategoriesAndMedia as $index => &$mediaCategory) {
            if (array_key_exists('parent_id', $mediaCategory)) {
                $parentId = $mediaCategory['parent_id'];

                $mediaCategoriesAndMedia[$parentId]['children'][] = &$mediaCategory;
            }
        }

        if ($this->getParentMediaCaetgoryId() == NULL) {
            return $mediaCategoriesAndMedia;
        }

        return $mediaCategoriesAndMedia[$this->getParentMediaCaetgoryId()];
    }

    /**
     * Returns aggregated media and categories with number of children.
     *
     * @param unknown $mediaCategories
     * @param array $aggregatedMediaAndCategories
     * @param number $level
     * @return void|array|unknown|mixed
     */
    public function getAggregatedMediaAndCategories($mediaCategories, &$aggregatedMediaAndCategories = [], $level = 0)
    {
        if ($mediaCategories == null) {
            return;
        }

        $level ++;

        if (array_key_exists('id', $mediaCategories)) {
            $categoryId = $mediaCategories['id'];
            $aggregatedMediaAndCategories[$categoryId]['media'][] = $mediaCategories['media'];

            if (! isset($aggregatedMediaAndCategories[$categoryId]['categories'])) {
                $aggregatedMediaAndCategories[$categoryId]['categories'] = [];
            }

            $mediaCategories = &$mediaCategories['children'];
        }

        while ($mediaCategory = array_shift($mediaCategories)) {
            if ($mediaCategory['parent_id'] != NULL) {
                $aggregatedMediaAndCategories[$mediaCategory['parent_id']]['categories'][$mediaCategory['id']] = $mediaCategory['name'];
            }

            if (count($mediaCategory['media']) > 0) {
                $aggregatedMediaAndCategories[$mediaCategory['parent_id']]['media'][] = $mediaCategory['media'];
            }

            if (count($mediaCategory['children']) > 0) {
                $aggregatedMediaAndCategories[$mediaCategory['id']]['category_name'] = $mediaCategory['name'];
                $this->getAggregatedMediaAndCategories($mediaCategory['children'], $aggregatedMediaAndCategories, $level);
            }
        }

        return $aggregatedMediaAndCategories;
    }

    /**
     * Merges nested media categories with parent categories.
     *
     * @param unknown $mediaOrCategories
     * @param unknown $aggregatedMediaAndCategories
     * @param array $collection
     * @return string
     */
    public function mergeNestedMediaCategoriesToParent($mediaOrCategories, $aggregatedMediaAndCategories, &$collection = [])
    {
        if (count($mediaOrCategories) > 0) {
            foreach ($mediaOrCategories as $index => $val) {
                $collection[] = $index . ":" . $val;

                if (isset($aggregatedMediaAndCategories[$index])) {
                    $mediaOrCategories = $aggregatedMediaAndCategories[$index]['categories'];
                    $this->mergeNestedMediaCategoriesToParent($mediaOrCategories, $aggregatedMediaAndCategories, $collection);
                }
            }
        }

        return $collection;
    }

    /**
     * Creates list from the tree structure
     *
     * @param unknown $tree
     * @param array $list
     * @param number $level
     * @return unknown|array
     */
    public function treeStructuretoList(&$tree, &$list = [], $level = 0)
    {
        if ($tree == NULL) {
            return $list;
        }

        $level ++;

        while ($leaf = array_shift($tree)) {
            $children = $leaf['children'];
            $leaf['children'] = [];
            $list[$leaf['id']] = $leaf;
            if (count($children) > 0) {
                $this->treeStructuretoList($children, $list, $level);
            }
        }

        return $list;
    }

    /**
     * Returns media category by category ids.
     *
     * @param unknown $categoryIds
     * @return mixed
     */
    public function getMediaCategoryByIds(Array $categoryIds)
    {
        return \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::query()->inWhere("id", $categoryIds)->execute();
    }

    /**
     * Inserts media category to database.
     *
     * @param unknown $data
     * @throws ManagerException
     * @return \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory
     */
    public function addMediaCategory($data)
    {
        $mediaCategoryModel = new \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory();
        if ($mediaCategoryModel->save($data)) {
            return $mediaCategoryModel;
        }

        throw new ManagerException($mediaCategoryModel->getFormattedMessages());
    }

    /**
     * Returns media categories and media filtered by ACL.
     */
    function getMediaCategoriesAndMediaFilteredByAcl($mediaCategoriesAndMedia, $mediaCategoryAcl = [803,811])
    {
        // Get the list of all media and media categories in a section id.
        // $mediaCategoriesAndMedia = $this->getMediaCategoriesAndMediaBySectionId();

        // Create the tree structure based on the list of all media and media categories in section id.
        $nestedMediaCategoriesAndMedia = $this->getChildMediaCategoriesAndMedia($mediaCategoriesAndMedia);

        // $mediaCategoryAcl = [803,811];
        // $mediaCategoryAcl = [802,803,804,805,806,807,808,809,810,811,812,813,814,815,816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832,833,834,835,836,837,838,839,840,841,842,843,845,846,847,848,849,850,851,852,853,855,856,857,167034,167035,167036,167037,167038,167039,167040,167041,167042,167043,167044,167045,167046,167047,167048,167049];

        $rootMediaCategoryIds = [];
        foreach ($mediaCategoryAcl as $_mediaCategoryId) {
            $mediaCategoriesAndMedia[$_mediaCategoryId] = $nestedMediaCategoriesAndMedia[$_mediaCategoryId];
            $rootMediaCategoryId = $this->_filterMediaCategoriesAndMediaByAcl($mediaCategoriesAndMedia[$_mediaCategoryId], $mediaCategoriesAndMedia);
            $rootMediaCategoryIds[$rootMediaCategoryId] = $rootMediaCategoryId;
        }

        $filteredMediaCategoriesAndMediaByAcl = [];
        if (in_array($this->getParentMediaCaetgoryId(), [
            0,
            NULL
        ])) {
            if (count($rootMediaCategoryIds) > 0) {
                foreach ($rootMediaCategoryIds as $rootMediaCategoryId) {
                    $filteredMediaCategoriesAndMediaByAcl[] = $mediaCategoriesAndMedia[$rootMediaCategoryId];
                }
            }
        }

        return $filteredMediaCategoriesAndMediaByAcl;
    }

    /**
     * Returns category maintaining the parent child relationship based on the access control list.
     *
     * @param unknown $mediaCategory
     * @param unknown $mediaCategoriesAndMedia
     * @param number $rootMediaCategoryId
     * @return unknown
     */
    private function _filterMediaCategoriesAndMediaByAcl(&$mediaCategory, &$mediaCategoriesAndMedia, &$rootMediaCategoryId = 0)
    {
        $parentId = $mediaCategory['parent_id'];
        $mediaCategoryId = $mediaCategory['id'];
        $rootMediaCategoryId = $mediaCategoryId;
        if ($parentId > 0) {
            $mediaCategoriesAndMedia[$parentId]['children'][] = &$mediaCategory;
            $this->_filterMediaCategoriesAndMediaByAcl($mediaCategoriesAndMedia[$parentId], $mediaCategoriesAndMedia, $rootMediaCategoryId);
        }

        return $rootMediaCategoryId;
    }

    /**
     * This function is invoked when event is fired \Library\Cloudscraper\Activity\Deal:afterDealCreated
     *
     * @param \Phalcon\Event $event
     * @param unknown $deal
     */
    public function afterDealCreated($event, $deal)
    {
        if (is_object($deal)) {
            /* ================ */

            $this->setSection(\Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::SECTION_DEAL);
            $this->setSectionId($deal->dealId);
            $defaultCategories = $this->getDefaultCategories();
            $newCategoryDetails = [
                'created_at' => $deal->created_at,
                'updated_at' => $deal->created_at,
                'created_by' => $deal->created_by
            ];

            $categories = $this->addDefaultCategories($defaultCategories, $newCategoryDetails);
            /* ================= */
        }
    }
}

class ManagerException extends \Phalcon\Exception
{
}