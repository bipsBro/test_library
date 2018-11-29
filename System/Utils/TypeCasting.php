<?php
namespace Library\System\Utils;

class TypeCasting
{

    /**
     * * Converts type * @param $destination Object destination * @param stdClass $source Source
     */
    public static function cast(&$destination, \stdClass $source)
    {
        $sourceReflection = new \ReflectionObject($source);
        $sourceProperties = $sourceReflection->getProperties();
        
        foreach ($sourceProperties as $sourceProperty) {
            
            $name = $sourceProperty->getName();
            
            // Check if the destination object has the source property.
            if (! property_exists($destination, $name)) {
                continue;
            }
            
            if (gettype($destination->{$name}) == "object") {
                if (is_array($source->{$name})) {
                    $i = 0;
                    foreach ($source->{$name} as $arr) {
                        $arrayObj = $destination->{$name}->factory((array) $arr);
                        $destination->{$name} = $arrayObj;
                        $destination->{$name}->append($arrayObj);
                    }
                    continue;
                }
                
                self::cast($destination->{$name}, $source->$name);
                continue;
            }
            
            $destination->{$name} = $source->$name;
        }
    }

    public static function merge($obj1, $obj2)
    {

        foreach (get_object_vars($obj1) as $var => $val) {
            
            if (empty($val)) {
                continue;
            }
            
            if (property_exists($obj2, $var)) {
                $obj2->{$var} = $val;
            }
        }
        return $obj2;
    }
}
?>