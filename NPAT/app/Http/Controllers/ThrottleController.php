<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ForgetThrottleRepository;

class ThrottleController extends Controller
{
    protected $forgetthrottleRepository;

    public function __construct(ForgetThrottleRepository $forgetThrottleRepository)
    {
        $this->forgetthrottleRepository=$forgetThrottleRepository;
    }



}
