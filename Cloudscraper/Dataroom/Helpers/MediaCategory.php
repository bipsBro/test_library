<?php 
namespace Library\Cloudscraper\Dataroom\Helpers;
class MediaCategory{
    public function generateBreadCrumb($breadCrumbPath,$params=[]){
        # Generate breadcrumb
        $requestManager = new \Library\System\Request\Manager();
        $mediaCategoriesForBreadcrumb = $requestManager->get('dataroom/media-categories', [
            'mediaCategoryIds' => str_replace("_", ",", $breadCrumbPath)
        ]);
        
        array_unshift($mediaCategoriesForBreadcrumb, [
            'id' => 0,
            'name' => "Dataroom"
        ]);
        
        $breadcrumbList = [];
        if(count($mediaCategoriesForBreadcrumb) >0){
            foreach ($mediaCategoriesForBreadcrumb as $index => $mediaCategory) {
                $mediaCategoryId = $mediaCategory['id'];
                $mediaCategoryName = $mediaCategory['name'];
                $breadcrumbList[] = $mediaCategoryId;
                $queryString = http_build_query(array_merge($params, [
                    'pcId' => $mediaCategoryId,
                    'bcs' => implode("_", $breadcrumbList)
                ]));
                
                $mediaCategoriesForBreadcrumb[$index] = [
                    'id' => $mediaCategoryId,
                    'name' => $mediaCategoryName,
                    'queryString' => $queryString
                ];
            }
        }
        
        return $mediaCategoriesForBreadcrumb;
    }
}
?>