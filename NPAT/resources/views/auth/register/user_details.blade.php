@extends('layouts.applayout')
@section('title')
    Register
@endsection
@section('menu')
    {{--*/ $permissionGroupSlug = 'users' /*--}}
    @include('partials.navigationMenu')
@endsection
@section('content')

<?php
$father_name = isset($userData['personalData']['father_name'])?$userData['personalData']['father_name']: '';
$marital_status = isset($userData['personalData']['marital_status'])? $userData['personalData']['marital_status']: '';
$dob = isset($userData['personalData']['date_of_birth'])? $userData['personalData']['date_of_birth']: '';
if($dob){
    $dob = $userData['personalData']->getViewDateFormat($dob);
}
$residential_address = isset($userData['personalData']['residential_address'] )? $userData['personalData']['residential_address']: '';
$present_address = isset($userData['personalData']['present_address'] )? $userData['personalData']['present_address']: '';
$contact_number = isset($userData['personalData']['mobile_number'])? $userData['personalData']['mobile_number']: '';
$landline = isset($userData['personalData']['landline'])? $userData['personalData']['landline']: '';
$personal_email = isset($userData['personalData']['personal_email'])? $userData['personalData']['personal_email']: '';
$pan_number = isset($userData['personalData']['pan_number'])? $userData['personalData']['pan_number']: '';
$aadhar_number = isset($userData['personalData']['aadhar_number'])? $userData['personalData']['aadhar_number']: '';

$previous_company_name = isset($userData['expData']['previous_company_name'])? $userData['expData']['previous_company_name']:'';
$prev_designation = isset($userData['expData']['previous_designation'])? $userData['expData']['previous_designation']:'';
$relevant_experience = isset($userData['expData']['relevent_exp'])? $userData['expData']['relevent_exp']:'';
$total_experience = isset($userData['expData']['total_exp'])? $userData['expData']['total_exp']:'';
$exp_in_current_org = isset($userData['expData']['organisation_exp'])? $userData['expData']['organisation_exp']:'';
$prev_ctc = isset($userData['expData']['previous_ctc'])? $userData['expData']['previous_ctc']:'';


$doj = isset($userData['companyData']['date_of_join'])? $userData['companyData']['date_of_join']: '';
$lwd = isset($userData['companyData']['last_working_day'])? $userData['companyData']['last_working_day']: '';
if($doj){
    $doj = $userData['personalData']->getViewDateFormat($doj);
}
if($lwd){
    $lwd = $userData['personalData']->getViewDateFormat($lwd);
}
$status = isset($userData['basicData']['status'])? $userData['basicData']['status']: '';
$department_id = isset($userData['companyData']['department_id'])? $userData['companyData']['department_id']: '';
$practices_id = isset($userData['basicData']['practices_id'])? $userData['basicData']['practices_id']: '';
$division_head = isset($userData['companyData']['division_head_id'])? $userData['companyData']['division_head_id']: '';
$probation_confirmation = isset($userData['companyData']['probation_confirmation'])? $userData['companyData']['probation_confirmation']: '';
$current_ctc = isset($userData['companyData']['ctc'])? $userData['companyData']['ctc']: '';

?>
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-body">
                {!! Form::open(array('url' => 'user-update/'.$userData['basicData']['emp_id'], 'method' => 'post', 'class' => 'form', 'id' => 'user-advanced-data')) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="overflowAuto">
                    <h1 class="accordion-toggle" data-toggle="collapse" id="first_section" data-target="#personal-details">Personal Details <span class="glyphicon glyphicon-minus-sign"></span></h1>

                    <div id="personal-details" class="collapse overflowAuto">
                        <div class="form-group  col-sm-4">
                            {!! Form::hidden('user_id', $userData['basicData']['id']) !!}
                            {!! Form::label('Employee Id') !!}
                            {!! Form::text('emp_id', $userData['basicData']['emp_id'], array('class'=>'form-control','readonly'=>'true'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Father Name') !!}
                            {!! Form::text('father_name', $father_name, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Marital status') !!}
                            <select class="form-control" id="marital-status" name="marital_status">
                                <option value="">Select</option>
                                <option value="single" {{ ($marital_status == 'single')?'selected':'' }}>Single</option>
                                <option value="married" {{ ($marital_status == 'married')?'selected':'' }}>Married</option>
                                <option value="divorcee" {{ ($marital_status == 'divorcee')?'selected':'' }}>Divorcee</option>
                            </select>
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Date of Birth') !!}
                            <input type="text" class="form-control" id="dob" name="date_of_birth" value="{{ $dob }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Residential Address') !!}
                            {!! Form::text('residential_address', $residential_address, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Present Address') !!}
                            {!! Form::text('present_address', $present_address, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Contact Number') !!}
                            <input type="number" class="form-control numberField" id="" min="0" name="contact_number" value="{{ $contact_number }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Landline') !!}
                            <input type="number" class="form-control numberField" id="" min="0" name="landline" value="{{ $landline }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Personal Email') !!}
                            {!! Form::email('personal_email', $personal_email, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('PAN Number') !!}
                            {!! Form::text('pan_number', $pan_number, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('AADHAR Number') !!}
                            {!! Form::text('aadhar_number', $aadhar_number, array('class'=>'form-control'))!!}
                        </div>
                    </div>
                </div>

                <div class="overflowAuto">
                    <h1 class="accordion-toggle" data-toggle="collapse" data-target="#exp-details">Navigator Experience details <span class="glyphicon glyphicon-plus-sign"></span></h1>
                    <div class="collapse overflowAuto" id="exp-details">
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Previous Company name') !!}
                            {!! Form::text('previous_company_name', $previous_company_name, array('class'=>'form-control'))!!}
                        </div>

                        <div class="form-group  col-sm-4">
                            {!! Form::label('Previous Company Designation') !!}
                            {!! Form::text('designation', $prev_designation, array('class'=>'form-control'))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Relevant experience') !!}
                            <input type="number" min="0" class="form-control numberField" step="any" id="" name="relevant_experience" value="{{ $relevant_experience }}">                            
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Total experience') !!}
                            <input type="number" min="0" class="form-control numberField" step="any" id="" name="total_experience" value="{{ $total_experience }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Experience In this Organisation') !!}
                            <input type="number" min="0" class="form-control numberField" step="any" id="" name="exp_in_current_org" value="{{ $exp_in_current_org }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Previous Company CTC') !!}
                            <input type="number" name="previous_ctc" min="0" id="" class="form-control numberField" value="{{ $prev_ctc }}">
                        </div>
                    </div>
                </div>

                <div class="overflowAuto">
                    <h1 class="accordion-toggle" data-toggle="collapse" id="current_exp_section" data-target="#current-details">Current Company details <span class="glyphicon glyphicon-plus-sign"></span></h1>
                    <div class="collapse overflowAuto" id="current-details">
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Date Of Joining') !!}
                            <input type="text" class="form-control" id="doj" name="date_of_join" value="{{ $doj }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Last Working Date') !!}
                            <input type="text" class="form-control" id="lwd" name="last_working_day" value="{{ $lwd }}">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Employee Status') !!}
                            <select class="form-control" id="emp-status" name="emp_status">
                                <option value="">Select Status</option>
                                <option value="1" {{ ($userData['basicData']['status'] == 1)?'selected':'' }}>Active</option>
                                <option value="0" {{ ($userData['basicData']['status'] == 0)?'selected':'' }}>InActive</option>
                            </select>
                        </div>                    
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Department') !!}
                            <span class="asteric">*</span>
                            <select class="form-control" id="department" name="department_id">
                                <option value="">Select Department</option>
                                @foreach($department as $depart)
                                    <option value="{{ $depart['id'] }}" {{ ($department_id == $depart['id'])?'selected':'' }}>{{ $depart['department_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Division') !!}
                            <select class="form-control" id="practices-id" name="division" disabled="true">
                                <option value="">Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division['id'] }}" {{ ($practices_id == $division['id'])?'selected':'' }}>{{ $division['practices']}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="practice_head_id" name="practice_head_id" value="0">
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Division Head') !!}
                            {!! Form::text('division_head', null, array('class'=>'form-control', 'id'=>'division-head', 'disabled' => "true"))!!}
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('Probation Confirmation') !!}                            
                            <select class="form-control" id="" name="probation_confirmation">
                                <option value="">Select Status</option>
                                <option value="1" {{ ($probation_confirmation == 1)?'selected':'' }}>Yes</option>
                                <option value="0" {{ ($probation_confirmation == 0)?'selected':'' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group  col-sm-4">
                            {!! Form::label('CTC') !!}
                            <input type="number" name="current_ctc" min="0" id="" class="form-control numberField" value="{{ $current_ctc }}">
                        </div>
                    </div>
                </div>

                <div class="overflowAuto">
                    <h1 class="accordion-toggle" data-toggle="collapse" data-target="#performance-details">Performance Details <span class="glyphicon glyphicon-plus-sign"></span></h1>
                    <div class="collapse overflowAuto" id="performance-details">
                        <?php $count = 1; ?>
                        @if(count($userData['performanceData']) >=1 )
                            
                            @foreach($userData['performanceData'] as $performance)
                                <div class="form-group  col-sm-3">
                                    {!! Form::label('Interim Hike '.$count) !!}
                                    <input type="number" class="form-control numberField" id="" min="0" name="intrim_hike_{{$count}}" value="{{ $performance->interim_hike }}">
                                </div>
                                <div class="form-group  col-sm-3">
                                    {!! Form::label('Rating '.$count) !!}
                                    <input type="number" class="form-control numberField" id="" min="0" max="5" step="any" name="rating_{{$count}}" value="{{ $performance->rating }}">
                                </div>
                                <div class="form-group  col-sm-3">
                                    {!! Form::label('Promotion '.$count) !!}
                                    <select class="form-control" id="promotion-{{ $count }}" name="promotion_{{ $count }}">
                                        <option >Select</option>
                                        <option value="1" {{($performance->promotion == 1)?'selected':''}}>Yes</option>
                                        <option value="0" {{($performance->promotion == 0)?'selected':''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group  col-sm-3">
                                    {!! Form::label('Compensation '.$count) !!}
                                    <input type="number" class="form-control numberField" id="" min="0" name="compensation_{{$count}}" value="{{ $performance->compensation }}">
                                </div>
                                <?php $count++; ?>
                            @endforeach
                        
                        @else                        
                            <div class="form-group  col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::label('Interim Hike (First half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" name="intrim_hike_1" value="">
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('Interim Hike (Second half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" name="intrim_hike_2" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::label('Rating (First half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" max="5" step="any" name="rating_1" value="">
                                    </div>

                                    <div class="col-sm-6">
                                        {!! Form::label('Rating (Second half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" max="5" step="any" name="rating_2" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::label('Promotion (First half)') !!}
                                        <select class="form-control" id="promotion-1" name="promotion_1">
                                            <option >Select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('Promotion (Second half)') !!}
                                        <select class="form-control" id="promotion-2" name="promotion_2">
                                            <option >Select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! Form::label('Compensation (First half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" name="compensation_1" value="">
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::label('Compensation (Second half)') !!}
                                        <input type="number" class="form-control numberField" id="" min="0" name="compensation_2" value="">
                                    </div>
                                </div>

                            </div>
                        @endif

                    </div>
                </div>

                <div class="centered">
                    <button type="submit" class="btn btn-default" name="sub">Submit</button>
                    <button type="button" class="btn btn-default" onclick="window.location='{{ route("register") }}'">Cancel</button>
                </div>
                
            </div>
            {!! Form::close() !!}
        </div>

    </div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $("#first_section").trigger("click");
    $( "#practices-id" ).trigger( "click" );
});
</script>
@endsection