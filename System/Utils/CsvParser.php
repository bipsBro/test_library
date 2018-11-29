<?php
namespace Library\System\Utils;

class CsvParser
{
    protected $_csvSource = "";
    
    /**
     * Sets Csv source path.
     * 
     * @param String $csvSource
     * @return \Library\System\Utils\CsvParser
     */
    public function setCsvSource($csvSource){
        $this->_csvSource = $csvSource;
        return $this;
    }
    
    /**
     * Return csv source file.
     * @return string|String
     */
    public function getCsvSource(){
        return $this->_csvSource;
    }
    
    /**
     * Returns parsed data
     */
    public function getData(){
        if (($handle = fopen($this->getCsvSource(), "r")) !== FALSE) {
            $parsedData = $dataFields = [];
            $ctr = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $ctr ++;
               if($ctr == 1){
                   $dataFields = $data;
                   continue;
               }
               $parsedData[] = array_combine($dataFields,$data);
            }
            
            fclose($handle);
            
            return $parsedData;
        }
    }
}