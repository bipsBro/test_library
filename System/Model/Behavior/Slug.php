<?php
namespace Library\System\Model\Behavior;
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;

class Slug extends Behavior implements BehaviorInterface
{
    public function notify($eventType, \Phalcon\Mvc\ModelInterface $model)
    {
        switch ($eventType) {
            case "prepareSave":
                if(!method_exists($model, 'getSlug')){
                    throw new \Library\System\Model\Behavior\SlugException("Cannot find the method getSlug in the model");
                }
                
                $model->slug = $model->getSlug();
                return $model;
                break;
            default:
                break;
        }
    }
}

class SlugException extends \Phalcon\Exception{}
?>