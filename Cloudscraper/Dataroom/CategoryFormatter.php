<?php

namespace Library\Cloudscraper\Dataroom;

class CategoryFormatter {
    
    protected $data;
    
    protected $formattedData;
    
    public function __construct($jsonString)
    {
        $this->setData($jsonString);
    }
    
    private function setData($unformattedData)
    {
        $this->data = $unformattedData;
    }
    
    /**
     *
     */
    public function getFormattedData()
    {
        $sxi = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($this->data),
            \RecursiveIteratorIterator::CATCH_GET_CHILD);
        
        $struct = [];
        
       
        //Prepare array result with depth/level of nested element category and parent value
        foreach ($sxi as $parent => $value) {
           
            // for all the first level tree nodes
            if(is_string($parent)){
                $struct[] = ['depth' => $sxi->getDepth(), 'category' => $parent, 'parent' => 0];
            }
            
            
            // for nested category structure
            if (is_array($value)) {
                $this->traverseInnerIterator(new \RecursiveArrayIterator($value), $parent, $sxi->getDepth(), $struct);
            }
        }
        
        return $struct;       
    }
    
    
    /**
     *
     * @param \RecursiveArrayIterator $iterator
     * @param string $parent
     * @param int $depth
     * @param array $struct
     * @return number[]|string[]|mixed[]
     */
    private function traverseInnerIterator(\RecursiveArrayIterator $iterator, $parent = null, $depth = null, &$struct)
    {
        // if node has children
        if ($iterator->hasChildren()) {
            
            $depth++;
            
            foreach ($iterator as $iter) {
                // process leaves
                if ($iterator->hasChildren()) {
                    //traverse through all the children
                    $this->processChildren($iterator->getChildren(), $parent, $depth, $struct);
                } else {
                    $struct[] = ['depth' => $depth , 'category' => $iter, 'parent' => $parent];
                }
            }
            
        } else {
            foreach ($iterator as $it) {
                $struct[] = ['depth' => $depth+1 , 'category' => $it, 'parent' => $parent];
            }
        }
        
        return $struct;
    }
    
    /**
     *
     * @param array | \RecursiveArrayIterator $childNodes
     * @param string $parent
     * @param int $depth
     * @param array $struct
     * @return string[]|unknown[]|string[][]|unknown[][]
     */
    private function processChildren($childNodes, $parent = null, $depth = null, &$struct)
    {
        foreach ($childNodes as $k => $v) {
            
            $struct[] = ['depth' => $depth , 'category' => $k, 'parent' => $parent];
            
            $parent = $k;
            
            $depth++;
            
            if (is_array($v)) {
                
                foreach ($v as $ite) {
                    if (is_array($ite) && $this->_isAssoc($ite)) {
                        $this->processChildren($ite, $parent, $depth, $struct);
                    } else {
                        $struct[] = ['depth' => $depth , 'category' => $ite, 'parent' => $parent];
                    }
                }
            }
            
        }
        
        return $struct;
    }
    
    
    /**
     *
     * @param array $arr
     * @return boolean
     */
    private function _isAssoc(array $arr){
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

