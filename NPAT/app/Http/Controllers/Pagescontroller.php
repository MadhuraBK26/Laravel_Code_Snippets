<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use App\Repositories\FeedbackRepository;
use App\Repositories\UserRepository;
use App\Repositories\UtilityRepository;
use App\User;
use Session;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    protected $feedbackRepository;
    protected $userRepository;
    protected $utilityRepository;

    public function __construct(FeedbackRepository $feedbackRepository, UserRepository $userRepository, UtilityRepository $utilityRepository)
    {
        parent::__construct();
        $this->feedbackRepository = $feedbackRepository;
        $this->userRepository = $userRepository;
        $this->utilityRepository = $utilityRepository;
    }

    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function getFormData()
    {
        $data = $this->feedbackRepository->getData();
        return view('feedback_form', $data);
    }

    /**
     * save the feedback form data
     * @return
     */
    public function saveData(StoreFeedbackRequest $request)
    {
        $userDetails = $this->userRepository->getRolesNameId();
        $userFeedbackStore = $this->feedbackRepository->getSave($userDetails);
        $start_month = $this->utilityRepository->getStringMonthFromDate(\Input::get('start'));
        $end_month = $this->utilityRepository->getStringMonthFromDate(\Input::get('end'));
        
        if ($userFeedbackStore) {
            Session::flash('success', 'Feedback Saved successfully for quarter '.$start_month.' - '.$end_month);
            return \Redirect::back();
        }
        Session::flash('error', 'Sorry! Feedback already exists for selected quarter '.$start_month.' - '.$end_month);
        return \Redirect::back();

    }

    /**
     *  update the feedback form data
     * @param Integer
     * @return Response
     */
    public function updateData($peopleFeedbackId)
    {
        $this->feedbackRepository->updateProjectPerformanceFeedback($peopleFeedbackId);
        Session::flash('success', 'Feedback Updated successfully.');
        if(Session::get('role') == config('custom.practiceLeadId')){
            return redirect()->route('adminReport');
        }
        return redirect()->route('report');
    }

    /**
     *  Save feedback form practice lead
     * @param Integer
     * @return Response
     */

    public function saveResourceData(Request $request)
    {
        $validator = $this->validate($request, [
            'fromyear' => 'required',
            'fromdate' => 'required',
            'resourceName' => 'required',
            'practiceName' => 'required'
        ]);      

        $userDetails = $this->userRepository->getRolesNameId();
        $userPracticeLeadFeedback = $this->feedbackRepository->getResourceSave($userDetails);
        $start_month = $this->utilityRepository->getStringMonthFromDate(\Input::get('start'));
        $end_month = $this->utilityRepository->getStringMonthFromDate(\Input::get('end'));

        if ($userPracticeLeadFeedback) {
            Session::flash('success', 'Feedback Data Saved successfully for quarter '.$start_month.' - '.$end_month);
            return redirect()->route('plfeedback.form');
        }
        Session::flash('error', 'Sorry! Feedback already exists for selected quarter '.$start_month.' - '.$end_month);
        return redirect()->route('plfeedback.form');
    }
}
