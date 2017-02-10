<?php

namespace App\Http\Controllers;

use App\Services\DataExportService;
use Auth;

class ExportDataController extends Controller
{
    /**
     * @var \App\Models\Expectation
     */
    private $expectation;

    /**
     * @var \App\Repositories\UserRepository
     */
    private $userRepository;
    private $reportRepo;

    /**
     * @var DataExportService
     */
    private $dataExportService;

    public function __construct(DataExportService $dataExportService, \App\Repositories\UserRepository $userRepository, \App\Repositories\ReportRepository $reportRepo, \App\Models\Expectation $expectation)
    {
        $this->dataExportService = $dataExportService;
        $this->userRepository = $userRepository;
        $this->reportRepo = $reportRepo;
        $this->expectation = $expectation;
    }

    /**
     * Fetching Rating Report
     * @param null $fileType ,$peopleId,$projectId,$managerId,$fromDate,$toDate
     * @return mixed
     */
    public function exportRatingReport($fileType = null, $peopleId = null, $projectId = null, $managerId = null, $fromDate = null, $toDate = null)
    {
        $user = $this->userRepository->get($peopleId);
        $data = $this->userRepository->getRatingDataForExportingToCsv($peopleId, $projectId, $managerId, $fromDate, $toDate);
        $this->dataExportService->setPrependRow(["Rating for: " . $user->name, "Project manager: " . $user->reporting_manager_name]);
        return $this->dataExportService->exportToCsv($user, $data, $fileType);
    }

    /**
     * Fetching Navigators Assigned to Project List
     * @param null $fileType
     * @return mixed
     */
    public function exportNavigatorsList($fileType = null)
    {
        $user = Auth::user();
        $data = $this->userRepository->getNavigatorListToCsv($user);
        return $this->dataExportService->exportDataToCsv($data, $fileType);
    }

    /**
     * Fetching Report Summary
     * @param null $fileType ,$fromyear,$toyear,$peopleId
     * @return mixed
     */
    public function exportReportSummaryList($fileType = null, $fromyear = null, $toyear = null, $peopleId = null)
    {
        $userId = Auth::user()->id;
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId);
        $data = $this->reportRepo->getNavigatorReportSummaryListToCsv($fromyear, $toyear, $peopleId, $getHierarchicalIds);
        return $this->dataExportService->exportReportSummaryDataToCsv($data, $fileType);
    }

    /**
     * Fetching Resource rating
     * @param null $fileType,$projectId,$fromyear,$toyear,$peopleId,$practicesId
     * @return array of rating data with csv file
     */
    public function exportRatingReportList($fileType = null, $projectId = null, $fromdate = null, $todate = null, $peopleId = null, $practicesId= null ){
        
        $data = [];$row = [];
        
        $userId = Auth::user()->id;
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId); 
        $projectResources = $this->userRepository->getUserDetailsBasedOnDate($projectId, $fromdate, $todate,
        $peopleId, $practicesId, $getHierarchicalIds);
        $resourceRatings = $this->userRepository->getResourceRatings($projectResources);
        $records = $this->userRepository->formPeopleDetails($projectResources);
        
        foreach($records as $record){
            $row['Practice name'] = $record['projectName'];
            $row['Resource name'] = $record['peopleName'];
            $row['Designation'] = $record['designationName'];
            $row['Month'] = $this->getMonthFromDate($record['start_date'], $record['end_date']);
            
            if(isset($resourceRatings[$record['peopleIdVal']][2])){
                    $rating = $resourceRatings[$record['peopleIdVal']][2];
            }else{
                $rating = $resourceRatings[$record['peopleIdVal']][1];
            }
            $row['Rating'] = ($rating == 0)?'N/A':$rating;
            $data[] = $row;
        }

        return $this->dataExportService->exportRatingReportListDataToCsv($data, $fileType);
    }

    function getMonthFromDate($start_date, $end_date){

        return $monthformat = date('M', strtotime($start_date)).' - '.date('M', strtotime($end_date));
    }
}
