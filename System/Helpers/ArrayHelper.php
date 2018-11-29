<?php
namespace Library\System\Helpers;

class ArrayHelper
{

    /**
     * Returns all the keys from multidimensional arrays.
     *
     * @param array $array
     * @return unknown[]
     */
    static function getRecursiveArrayKeys(array $array)
    {
        $keys = array();
        
        foreach ($array as $key => $value) {
            $keys[] = $key;
            if (is_array($value)) {
                $keys = array_merge($keys, self::getRecursiveArrayKeys($value));
            }
        }
        
        return $keys;
    }

    /**
     * Checks if the two array has identical keys.
     *
     * @param Array $arr
     * @param Array $expectedKeys
     * @return boolean
     */
    static function isArrayKeysValid(array $arr, array $expectedKeys)
    {
        if (count($arr) <= 0 || count($expectedKeys) <= 0) {
            throw new \Library\System\Helpers\ArrayHelperException("Invalid array or expected keys path.");
        }
        
        $arrKeys = self::getRecursiveArrayKeys($arr);
        
        $commonKeys = (array) array_intersect($expectedKeys, $arrKeys);
        
        if ($expectedKeys == $commonKeys) {
            return true;
        }
        
        return false;
    }

    /**
     * Converts array to a given object
     */
    public static function toObject(Array $array, $targetObject)
    {
        if (empty($array) || ! is_object($targetObject)) {
            throw new \Library\System\Helpers\ArrayHelperException("Invalid object");
        }
        
        // Get all the properties in a target object.
        $targetObjectReflectionClass = new \ReflectionClass($targetObject);
        $targetObjectProperties = $targetObjectReflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $_targetObjectProperties = [];
        
        foreach ($targetObjectProperties as $targetObjectProperty) {
            array_push($_targetObjectProperties, $targetObjectProperty->name);
        }
        
        $targetObjectProperties = $_targetObjectProperties;
        $propertiesToSet = array_intersect(array_keys($array), $targetObjectProperties);
        
        foreach ($propertiesToSet as $property) {
            $propertyName = explode("_", $property);
            $propertyName = array_map('ucfirst', $propertyName);
            array_unshift($propertyName, "set");
            $method = implode("", $propertyName);
            call_user_func_array([
                $targetObject,
                $method
            ], [
                $array[$property]
            ]);
        }
        
        return $targetObject;
    }

    /**
     * Traverse a tree that is in the structure
     * $tree = [
     * 'name' => string,
     * 'id' => integer,
     * 'children' => [
     * [
     * 'name' => string,
     * 'id' => integer
     * ]
     * ]
     * ]
     *
     * @param array $tree
     * @param array $callback
     * @param array $list
     * @param number $level
     * @return unknown|mixed|unknown
     */
    public static function traverseTree($tree, $callback = [], &$list = [], $level = 0)
    {
        if ($tree == NULL) {
            return $list;
        }
        
        $level ++;
        
        while ($leaf = array_shift($tree)) {
            if (is_array($leaf)) {
                if (count($callback) > 0) {
                    $list[$leaf['id']] = call_user_func_array($callback, [
                        $leaf,
                        &$list,
                        $level
                    ]);
                } else {
                    $list[$leaf['id']] = $leaf;
                }
                
            }
            
            if (count($leaf['children']) > 0) {
                self::traverseTree($leaf['children'], $callback, $list, $level);
            }
        }
        
        return $list;
    }

    /**
     * Iterates nested multi dimensional array without children key.
     * 
     * @return number[][]|mixed[][]
     */
    public function iterateNestedMultiDimensionalArrayRecursively(&$data,&$struct=[],$depth = 0)
    {
        $sxi = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data), \RecursiveIteratorIterator::CATCH_GET_CHILD);
        
  
        // Prepare array result with depth/level of nested element category and parent value
        foreach ($sxi as $parent => $value) {
            
            // for all the first level tree nodes
            if (is_string($parent)) {
                $struct[] = [
                    'depth' => $sxi->getDepth(),
                    'category' => $parent,
                    'parent' => 0,
                ];
            }
            
            // for nested category structure
            if (is_array($value)) {
                $this->_traverseInnerIterator(new \RecursiveArrayIterator($value), $parent, $sxi->getDepth(), $struct);
            }
        }
        
        return $struct;
    }
    
    
    /*
     * Returns array indexed with primary key field.
     */
    public static function groupArrayByPrimaryKey($arr, $primaryKeyField)
    {
        $arrayGroupedByPrimaryKey = [];
        $formattedArray = [];
        array_walk($arr, function ($value) use (&$arrayGroupedByPrimaryKey, $primaryKeyField) {
            $arrayGroupedByPrimaryKey[$value[$primaryKeyField]] = $value;
        });
            
        return $arrayGroupedByPrimaryKey;
    }
    
   
    
    /**
     *
     * @param \RecursiveArrayIterator $iterator
     * @param string $parent
     * @param int $depth
     * @param array $struct
     * @return number[]|string[]|mixed[]
     */
    private function _traverseInnerIterator(\RecursiveArrayIterator $iterator, $parent = null, $depth = null, &$struct)
    {
        // if node has children
        if ($iterator->hasChildren()) {
            
            $depth ++;
            
            foreach ($iterator as $iter) {
                // process leaves
                if ($iterator->hasChildren()) {
                    // traverse through all the children
                    $this->_processChildren($iterator->getChildren(), $parent, $depth, $struct);
                } else {
                    $struct[] = [
                        'depth' => $depth,
                        'category' => $iter,
                        'parent' => $parent
                    ];
                }
            }
        } else {
            foreach ($iterator as $it) {
                $struct[] = [
                    'depth' => $depth + 1,
                    'category' => $it,
                    'parent' => $parent
                ];
            }
        }
        
        return $struct;
    }

    /**
     *
     * @param
     *            array | \RecursiveArrayIterator $childNodes
     * @param string $parent
     * @param int $depth
     * @param array $struct
     * @return string[]|unknown[]|string[][]|unknown[][]
     */
    private function _processChildren($childNodes, $parent = null, $depth = null, &$struct)
    {
        foreach ($childNodes as $k => $v) {
            
            $struct[] = [
                'depth' => $depth,
                'category' => $k,
                'parent' => $parent
            ];
            
            $parent = $k;
            
            $depth ++;
            
            if (is_array($v)) {
                
                foreach ($v as $ite) {
                    if (is_array($ite) && $this->_isAssoc($ite)) {
                        $this->_processChildren($ite, $parent, $depth, $struct);
                    } else {
                        $struct[] = [
                            'depth' => $depth,
                            'category' => $ite,
                            'parent' => $parent
                        ];
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
    private function _isAssoc(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

class ArrayHelperException extends \Phalcon\Exception
{
}