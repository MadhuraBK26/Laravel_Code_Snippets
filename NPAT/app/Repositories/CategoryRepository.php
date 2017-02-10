<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\FeedbackMetrics;
use Request;


class CategoryRepository extends LaravelJqgridRepositoryService
{
    public function __construct()
    {
        $this->Database = DB::table('kra_category')
            ->select('id', 'name', 'description', 'sort', 'color');
        $this->visibleColumns = array('id', 'name', 'description', 'sort', 'color');
        $this->orderBy = array(array('id', 'asc'));
    }
}
