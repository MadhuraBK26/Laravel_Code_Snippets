<div class="col-md-8">
    <div class="panel panel-info">
        <div class="panel-heading">Public Profile</div>
        <div class="panel-body">
            {!! Form::open(array('route' => 'user-profile', 'class' => 'form', 'id' => 'user-profile')) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group  col-sm-10">
                {!! Form::label('Name') !!}
                {!! Form::text('name', $user->name,
                array(
                'class'=>'form-control form-box','disabled',
                'placeholder'=>'Enter Your Name'))!!}
            </div>
            <div class="form-group  col-sm-10">
                {!! Form::label('Employee-Id') !!}
                {!! Form::text('emp_id', $user->emp_id,
                array(
                'class'=>'form-control','disabled',
                'placeholder'=>'Enter your EmployeeId '))!!}
            </div>

            <div class="form-group  col-sm-10">
                {!! Form::label('E-mail Address') !!}
                {!! Form::text('email', $user->email,
                array(
                'class'=>'form-control form-box','disabled',
                'placeholder'=>'Enter your Email Address'))!!}
            </div>

            <div class="form-group  col-sm-10">
                {!! Form::label('Designation') !!}
                {!! Form::text('userDesignation', $userDesignation['name'],
                array(
                'class'=>'form-control','disabled',
                'placeholder'=>'Enter Your Designation'))!!}
            </div>

            <div class="form-group  col-sm-10">
                {!! Form::label('Practice') !!}
                {!! Form::text('userPractices', $userPractices,
                array(
                'class'=>'form-control','disabled',
                'placeholder'=>'Enter Your Practice'))!!}
            </div>

            @if($reportingManager)
                <div class="form-group  col-sm-10">
                    {!! Form::label('Reporting Manager') !!}
                    {!! Form::text('reporter', $reportingManager['name'],
                    array(
                    'class'=>'form-control','disabled'))!!}
                </div>
            @endif

            <div class="form-group col-sm-10">
                <button class="btn btn-default editBtn" type="button"><i class="glyphicon glyphicon-pencil"></i>
                </button>

                {!! Form::submit('Update Profile',
                array('class'=>'btn btn-primary hideBtn', 'id'=>'form-submit')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
