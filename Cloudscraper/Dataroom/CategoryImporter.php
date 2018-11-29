<?php
namespace Library\Cloudscraper\Dataroom;

use Library\Cloudscraper\Dataroom\Models\CsxMediaCategory;


class CategoryImporter implements ImporterInterface
{
    public $categoryData;
    
    private $nestedLevel;
    
    private $sectionType;
    
    /**
     *
     * @param array $data
     * @param object $dbConn
     */
    public function __construct(array $data, $section)
    {
        $this->setData($data);
        $this->setSectionType($section);
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \Library\Cloudscraper\Dataroom\ImporterInterface::setData()
     */
    public function setData($data)
    {
        $this->categoryData = $data;
    }
    
    
    public function setSectionType($section)
    {
        $this->sectionType = $section;
    }
    
    
    
    public function import()
    {
        $importCount = 0;
       
        if (count($this->categoryData) > 0) {
            foreach ($this->categoryData as $category) {
                
                $mediaCategoryModel = new \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory();
                
                if ($category['depth'] >= 1) {
                    $parentWhere = $category['depth'] > 1 ? 'IS NOT NULL' : null;
                    $parentCategoryResult = $mediaCategoryModel->checkCategoryExists($category['parent'], $this->sectionType, $parentWhere);
                } else {
                    $result = $mediaCategoryModel->checkCategoryExists($category['category'], $this->sectionType, null);
                }
                if ($category['depth'] == 0) {
                    if (!$result) {
                        // insert category into table
                        $mediaCategoryModel->save(['name' => $category['category'],'section' => $this->sectionType, 'parent_id' => null, 'created_by' => 1]);
                        $importCount++;
                    }
                } else {
                    if ($parentCategoryResult) {
                        $result = $mediaCategoryModel->checkCategoryExists($category['category'], $this->sectionType, $parentCategoryResult['id']);
                        
                        if (!$mediaCategoryModel->checkCategoryExists($category['category'], $this->sectionType, $parentCategoryResult['id'])) {
                            // insert category into table
                            $mediaCategoryModel->save(['name' => $category['category'], 'section' => $this->sectionType, 'parent_id' => $parentCategoryResult['id'], 'created_by' => 1]);
                            $importCount++;
                        }
                    }
                }
            }
        }
        echo $importCount." {$this->sectionType} Imported";
    }
    
    
}

