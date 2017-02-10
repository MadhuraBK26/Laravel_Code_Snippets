<?php

namespace App\Services;

/**
 * Description of DataExportService
 *
 * @author jeevan
 */
class DataExportService
{
    private $prependRow = [];

    /**
     * Converting Data into Exporting CSV File
     * @param $user ,$data,$fileType,$fileName
     * @return mixed
     */
    public function exportToCsv($user, $data, $fileType = 'csv', $fileName = null)
    {
        if (!$fileName) {
            $fileName = "$user->name-$user->emp_id";
        }

        return \Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('sheet-1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
                $this->prependRow($sheet);
            });
        })->download($fileType);
    }

    public function setPrependRow($row)
    {
        $this->prependRow = $row;
    }

    public function prependRow($sheet)
    {
        if (count($this->prependRow) > 0) {
            $sheet->prependRow($this->prependRow);
        }
        return $sheet;
    }

    /**
     * Converting Navigators Data into CSV File
     * @param $data ,string $fileType,$fileName
     * @return mixed
     */
    public function exportDataToCsv($data, $fileType = 'csv', $fileName = null)
    {

        if (!$fileName) {
            $fileName = "Navigator Details";
        }
        return \Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('sheet-1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
                $this->prependRow($sheet);
            });
        })->download($fileType);

    }

    /**
     * Converting Report Summary into CSV File
     * @param $data ,string $fileType,$fileName
     * @return mixed
     */
    public function exportReportSummaryDataToCsv($data, $fileType = 'csv', $fileName = null)
    {
        if (!$fileName) {
            $fileName = "Report Summary";
        }
        return \Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('sheet-1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
                $this->prependRow($sheet);
            });
        })->download($fileType);
    }

    public function exportRatingReportListDataToCsv($data, $fileType = 'csv', $fileName = null){
        if (!$fileName) {
            $fileName = "Rating Report List";
        }
        return \Excel::create($fileName, function ($excel) use ($data) {
            $excel->sheet('sheet-1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
                $this->prependRow($sheet);
            });
        })->download($fileType);
    }
}
