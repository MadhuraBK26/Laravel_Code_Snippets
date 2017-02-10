<?php

namespace App\Repositories;

use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;
use App\Models\FeedbackMetrics;

class MetricsRepository extends EloquentRepositoryAbstract
{

    /**
     * Regex validation for metrics
     * @var array
     */
    public $validationRules = [
        'sort' => 'required|numeric',
    ];


    public function __construct()
    {
        $this->Database = FeedbackMetrics::join('kra_category','kra_category.id','=','feedback_metrics.category_id')
            ->select('feedback_metrics.*','kra_category.id as category_id','kra_category.name as category_name');
        $this->visibleColumns = array('id', 'metrics', 'category_id', 'sort');
        $this->orderBy = array(array('id', 'asc'), array('name', 'desc'));
    }

    /**
     * Stores newly created metrics
     * @param $metrics
     * @param $category
     * @param $sort
     * @param $status
     * @return string
     */
    public function storeNewMetrics($metrics, $category, $sort, $status)
    {
        $Metric = new FeedbackMetrics();
        $Metric->metrics = $metrics;
        $Metric->category_id = $category;
        $Metric->sort = $sort;
        $Metric->status = $status;
        try {
            $Metric->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric successfully saved!'));
    }

    /**
     * Update exisiting metrics
     * @param $id
     * @param $metrics
     * @param $category
     * @param $sort
     * @param $status
     * @return string
     */
    public function updateMetrics($id, $metrics, $category, $sort, $status)
    {
        $Metric = FeedbackMetrics::find($id);
        $Metric->metrics = $metrics;
        $Metric->category_id = $category;
        $Metric->sort = $sort;
        $Metric->status = $status;
        try {
            $Metric->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Metric successfully updated!'));
    }
}
