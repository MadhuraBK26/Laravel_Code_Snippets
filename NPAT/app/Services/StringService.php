<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StringService
 *
 * @author jeevan
 */
class StringService
{
    public function snakeCase($value)
    {
        return snake_case($value);
    }

    public function studlyCase($value)
    {
        return studly_case($value);
    }

    public function classBasename($value)
    {
        return class_basename($value);
    }

    public function getClassNameForUrl($fullClassName)
    {
        $baseClassName = $this->classBasename($fullClassName);

        return $this->snakeCase($baseClassName);
    }

    public function getClassNameFromUrl($urlClassName, $nameSpace = "App\Models")
    {
        $baseClassName = $this->studlyCase($urlClassName);

        return $nameSpace."\\".$baseClassName;
    }
}
