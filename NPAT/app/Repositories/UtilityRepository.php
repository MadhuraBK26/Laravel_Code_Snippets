<?php

namespace App\Repositories;

use Config;
use custom;
class UtilityRepository
{
    /**
     * Create a uiDateFormat method to format date in UI
     * @param $uiDate String
     * @return $uiDate
     */
    public function uiDateFormat($fromdate, $todate)
    {

        $uiFromDate = $this->dateStringToTimeStamp($fromdate);
        $uiToDate = $this->dateStringToTimeStamp($todate);
        $startDate = date(Config::get('util.uiConfigDate'), $uiFromDate);
        $endDate = date(Config::get('util.uiConfigDate'), $uiToDate);
        return array('startDate' => $startDate, 'endDate' => $endDate);
    }

    /**
     * convert Date String into timestamp
     * @param  String $dateString
     * @return integer Timestamp
     */
    private function dateStringToTimeStamp($dateString)
    {
        return strtotime($dateString);
    }

    /**
     * getFullYear in date
     * @param  String $dateString
     * @return String year
     */
    protected function getFullYear($dateString)
    {
        return date('Y', $this->dateStringToTimeStamp($dateString));
    }


    /**
     * get AlphabetMonth in date
     * @param  String $dateString
     * @return String Month - ex July,April
     */
    protected function getAlphabetMonth($dateString)
    {
       return date('M', $this->dateStringToTimeStamp($dateString));
    }

    /**
     * getMonth in date
     * @param  String $dateString
     * @return String Month - ex 01,02
     */
    protected function getMonth($dateString)
    {
       return date('m', $this->dateStringToTimeStamp($dateString));
    }

    /**
     * getYearMonthDate by start and End Date
     * @param $start_date String
     * @param $end_date String
     * @return Year Month Date
     */
    public function getYearMonthDate($start_date, $end_date)
    {
    return date("Y-m-d", $this->dateStringToTimeStamp($dateString));
    }

    /**
     * Create a dbDateFormat method to format date in DB
     * @param $dbDate String
     * @return $dbDate
     */
    public function dbDateFormat($dbDate)
    {
        return date(Config::get('util.dbConfigDate'), 
                $this->dateStringToTimeStamp($dbDate));
    }

    /**
     * getQuarter by start and End Date
     *
     * @param $start_date String
     * @param $end_date String
     * @return String Quarater
     */
    public function getQuarter($start_date, $end_date)
    {
        $quarter = $this->getAlphabetMonth($start_date) .'-'. $this->getAlphabetMonth($end_date);
        return array_search($quarter, config('custom.months'));
       
    }

     /**
     * getQuarter by start and End Date
     *
     * @param $start_date String
     * @param $end_date String
     * @return Array Year Month
     */
    public function getYearFromDate($start_date,$end_date)
    {
        return array(
            'year' => $this->getFullYear($start_date),
            'quarterMonth' => $this->getQuarter($start_date, $end_date),
            );
    }

    public function getStringMonthFromDate($inputDate)
    {
        return date("M", strtotime($inputDate));
    }

    public function getDateFormat($format, $inputDate){
        return date($format, strtotime($inputDate));
    }

    public function getCurrentDateWithFormat($format){
        return date ($format);
    }
}
