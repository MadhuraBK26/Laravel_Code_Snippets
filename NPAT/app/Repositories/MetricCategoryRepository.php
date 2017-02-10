<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\FeedbackMetrics;
use Request;


class MetricCategoryRepository extends LaravelJqgridRepositoryService
{
    public function __construct()
    {
        $this->Database = DB::table('feedback_metrics')
            ->select('feedback_metrics.*', 'kra_category.name as category_name')
            ->join('kra_category', 'feedback_metrics.category_id', '=', 'kra_category.id');
        $this->visibleColumns = array('id', 'metrics', 'category_id', 'sort', 'status');
        $this->orderBy = array(array('id', 'asc'));
    }
}


