<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReflectionClassService
 *
 * @author jeevan
 */
class ReflectionClassService
{
    public function getClassName($object)
    {
        return trim(get_class($object));
    }

    public function getPrimaryKeyValue($object)
    {
        $primaryKeyValue = null;
        if (property_exists($object, 'id')) {
            $primaryKeyValue = $object->id;
        } else {
            $primaryKeyFieldName = $object->getKeyName();
            $primaryKeyValue = $object->$primaryKeyFieldName;
        }

        return (int) ($primaryKeyValue);
    }
}
