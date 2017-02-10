<?php
namespace App\Repositories;

use App\Models\Project;
use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\Practices;
use Request;


class PracticesRepository extends LaravelJqgridRepositoryService
{

    public $validationRules = [
        'practices' => 'required|regex:/(^[A-Za-z\s]+$)+/',
        'status' => 'required',
    ];


    public function __construct()
    {
        $this->Database = DB::table('practices')
            ->select('practices.*',DB::raw("(CASE WHEN (practices.status = 1) THEN 'Active' ELSE 'InActive' END) as status"));
        $this->visibleColumns = array('id', 'practices', 'status');
        $this->orderBy = array(array('id', 'asc'));
    }

    /**
     * Add New Practices
     * @param $practicesName ,$status
     * @return string
     */
    public function storeNewPractices($practicesName, $status)
    {
        $practices = new Practices();
        $practices->practices = $practicesName;
        $practices->status = $status;
        try {
            $practices->save();

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'New Practices Created successfully !'));

    }

    /**
     *Update Existing Practices
     * @param $id ,$practicesName,$status
     * @return string
     */
    public function updatePracticesDetails($id, $practicesName, $status)
    {
        $practices = Practices::find($id);
        $practices->practices = $practicesName;
        $practices->status = $status;
        try {
            $practices->save();

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Practices Details Updated successfully !'));
    }

}