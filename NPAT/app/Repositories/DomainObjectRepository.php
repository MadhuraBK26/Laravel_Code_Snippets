<?php

namespace App\Repositories;

use App\Models\DomainObject as DomainObject;
use App\Models\User as User;

class DomainObjectRepository
{

    protected $user;
    protected $domainObject;

    /**
     * Injecting User model and DomainObject model object via constructor
     * @param User $user
     * @param DomainObject $domainObject
     */
    public function __construct(User $user, DomainObject $domainObject)
    {
        $this->user = $user;
        $this->domainObject = $domainObject;
    }
    
    /**
     * Gets DomainObject object from model name and its primary key value
     * @param string $modelName
     * @param integer $modelPrimaryKeyValue
     * @return DomainObject
     */
    public function getDomainObject($modelName, $modelPrimaryKeyValue)
    {
        return $this->domainObject->where("model_name", "=", $modelName)
                        ->where("primary_key_value", "=", $modelPrimaryKeyValue)
                        ->first();
    }
    
    /**
     * Creates DomainObject from model name and the primary key value
     * @param string $modelName
     * @param integer $modelPrimaryKeyValue
     * @return DomainObject
     */
    public function createDomainObject($modelName, $modelPrimaryKeyValue)
    {
        $domainObject = null;
        if ($modelName && $modelPrimaryKeyValue > 0) {
            $domainObject = new DomainObject();
            $domainObject->model_name = $modelName;
            $domainObject->primary_key_value = $modelPrimaryKeyValue;
            $domainObject->save();
        }

        return $domainObject;
    }
    
    /**
     * Checks if DomainObject object with the given
     * model name and the primary key value exists then return it 
     * or create and return it
     * @param string $modelName
     * @param integer $modelPrimaryKeyValue
     * @return DomainObject
     */
    public function getOrCreateDomainObject($modelName, $modelPrimaryKeyValue)
    {
        $domainObject = $this->getDomainObject($modelName, $modelPrimaryKeyValue);
        if (!$domainObject) {
            $domainObject = $this->createDomainObject($modelName, $modelPrimaryKeyValue);
        }

        return $domainObject;
    }

}
