<?php
/**
 * This class manages dataroom section . 
 * Media related to a resources in a dataroom is defined as section . 
 * For example deal, assets.
 */
namespace Library\Cloudscraper\Dataroom\Traits;

Trait Section
{
    /**
     * Collection of section
     * @var array
     */
    protected $_sections = [];
    
    
    /*
     * Sets section ASSET, DEAL or SYNDICATION_DEAL
     */
    protected $_section = "";
    
    /**
     * Maps section column with section type
     * format: "table column" =>  "section type"
     * @var array
     */
    protected $_sectionTypes = [
        "deal_id" => \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::SECTION_DEAL,
        "asset_id" =>\Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::SECTION_ASSET,
        "syndication_deal_id" => \Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory::SECTION_SYNDICATION_DEAL,
    ];
    /**
    * 
    *@var $_dealId;
    */
    protected $_dealId;
    
    /**
     *
     *@var  $_assetId;
     */
    protected $_assetId;
    
    /**
     * Set syndication deal.
     */
    protected $_syndicationDealId;
    
    /**
     * Set section id.
     */
    protected $_sectionId;
    
    /**
     * Set deal id.
     * 
     * @param Integer $dealId
     * @return \Library\Cloudscraper\Dataroom\Traits\Section
     */
    public function setDealId($dealId){
        $this->_dealId =$dealId;
        $this->_sections['deal_id'] = $dealId; 
        return $this;
    }
    
    /**
     * Return deal id.
     * 
     * @return Integer
     */
    public function getDealId(){
        return $this->_dealId ;
    }
    
    /**
     * Set asset id.
     * 
     * @param Integer $assetId
     * @return \Library\Cloudscraper\Dataroom\Traits\Section
     */
    public function setAssetId($assetId){
        $this->_assetId =$assetId;
        $this->_sections['asset_id'] = $assetId;
        return $this;
    }
    
    /**
     * Return asset id.
     * 
     * @return Integer
     */
    public function getAssetId(){
        return $this->_assetId ;
    }
    
    /**
     * 
     */
    public function setSyndicationDealId($syndicationDealId){
        $this->_syndicationDealId = $syndicationDealId;
        $this->_sections['syndication_deal_id'] = $syndicationDealId;
        return $this;
    }
    
    public function getSyndicationDealId(){
        return $this->_syndicationDealId;
    }
    
    /**
     * Returns section that 
     */
    public function getSectionKey(){
        $section = array_filter($this->_sections);
        if(count($section) > 0){
            return array_shift(array_keys($section));
        }
        
        return false;
    }
    
    /**
     * Returns section value.
     * 
     * @return mixed|boolean
     */
    public function getSectionValue(){
        $section = array_filter($this->_sections);
        if(count($section) > 0){
            return array_shift(array_values($section));
        }
        
        return false;
    }
    
    /**
     * Returns section type.
     * 
     * @return string|boolean
     */
    public function getSectionType(){
        $section = array_filter($this->_sections);
        if(count($section) > 0){
            $sectionKey = array_shift(array_keys($section));
            return $this->_sectionTypes[$sectionKey];
        }else{
            throw new SectionException(sprintf("Please specify the section (%s)",array_values($_sectionTypes)));
        }
        
        return false;
    }
    
    /*
     * Sets single section DEAL or ASSET or SYNDICATION_DEAL
     */
    public function setSection($section){
        $this->_section  = strtoupper($section);
        return $this;
    }
    
    /**
     * Return section 
     * @return string|unknown
     */
    public function getSection(){
        if(empty($this->_section)){
            throw new SectionException(sprintf("Please specify the section"));
        }
        return $this->_section;
    } 
    
    public function setSectionId($sectionId){
        $this->_sectionId = $sectionId;
        return $this;
    }
    
    public function getSectionId(){
        return $this->_sectionId;
    }
}

class SectionException extends \Phalcon\Exception{}