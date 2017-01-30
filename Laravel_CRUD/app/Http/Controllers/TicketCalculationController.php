<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\pTicketCalculation;

use Input;
use Illuminate\Support\Facades\Log;
use App\Exception\Handler;
use App\Http\Requests;
use App\Http\Controllers\Controller; 
use App\registers;
use Illuminate\Database\QueryException;  

class TicketCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {    
         try {
                $tickets = pTicketCalculation::orderBy('id','DESC')->paginate(5);
                return view('pTicketCalculationCRUD.index',compact('tickets'));
         }
         catch (\Exception $e) {
                return $e->getMessage();
         }

             // ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        try {
                return view('pTicketCalculationCRUD.create');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [

        'name' => 'required',
        'carNumber' => 'required',
        'carModel' => 'required',
        'farePerDay' => 'required',
        'noOfDays' => 'required',
        'noOfCars' => 'required',
        ]);
        
        $noOfCars = Input::get('noOfCars');
        $noOfCar= implode(",",$noOfCars);
        //echo $noOfCar;
        $request['noOfCars'] = $noOfCar;
        $cars = Input::get('noOfCars');

        try {
                pTicketCalculation::create($request->all());
                return redirect()->route('pTicketCalculationCRUD.index')
                ->with('success','Item created successfully');
        }
        catch (\Exception $e) {
           // Log::info('Error occured: ' . $e);
           // return response()->view('errors.custom', [], 500);
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
               $ticket = pTicketCalculation::find($id);
               return view('pTicketCalculationCRUD.show',compact('ticket'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        try { 
                $farePerDay = (isset($_POST['farePerDay'])=='1'?'1':'0'); 
                $ticket = pTicketCalculation::find($id);
                return view('pTicketCalculationCRUD.edit',compact('ticket'));
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        'name' => 'required',
        'carNumber' => 'required',
        'carModel' => 'required',
        'farePerDay' => 'required',
        'noOfDays' => 'required',
        'noOfCars' => 'required',
        ]);
        $noOfCars = Input::get('noOfCars');
        $noOfCar = implode(",",$noOfCars);
        $request['noOfCars'] = $noOfCar;
        $cars = Input::get('noOfCars');
        try {
                pTicketCalculation::find($id)->update($request->all());
                return redirect()->route('pTicketCalculationCRUD.index')
                ->with('success','Item updated successfully');
        }
        catch (\Exception $e){
            return $e->getMessage();
            echo "Error occured";
                   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        pTicketCalculation::find($id)->delete();
        return redirect()->route('pTicketCalculationCRUD.index')
        ->with('success','Item deleted successfully');
    }
}
