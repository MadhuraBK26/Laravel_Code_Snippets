<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pTicketCalculation extends Model
{
	protected $table = 'pTicketCalculation';
    public $fillable = ['name','carNumber','carModel','farePerDay','noOfDays','noOfCars'];
}
