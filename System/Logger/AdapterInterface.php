<?php
namespace Library\System\Logger;
interface AdapterInterface
{
    public static function factory(\Phalcon\Config $config);
    public  function getTransactions();
    public function getFormattedTransactions();
}
