<?php

namespace App\Repositories;

use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;

class UserGridRepository extends EloquentRepositoryAbstract
{

    public function __construct()
    {
        $this->Database       = new User();
        $this->visibleColumns = array('id', 'name', 'role_id', 'navigator_designation_id');
        $this->orderBy        = array(array('id', 'asc'), array('metrics', 'desc'));
    }
}
