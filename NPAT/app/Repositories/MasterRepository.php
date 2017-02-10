<?php

namespace App\Repositories;

use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;
use \Illuminate\Database\Eloquent\Model;

class MasterRepository extends EloquentRepositoryAbstract
{

    public function __construct(Model $model, array $visibleColumns)
    {
        $this->Database       = $model;
        $this->visibleColumns = $visibleColumns;
        $this->orderBy        = array(array('id', 'asc'));
    }
}
