@extends('layouts.default')

 

@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Edit Parking Details</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('pTicketCalculationCRUD.index') }}"> Back</a>

            </div>

        </div>

    </div>


    @if (count($errors) > 0)

        <div class="alert alert-danger">

            <strong>Whoops!</strong> There were some problems with your input.<br><br>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {!! Form::model($ticket, ['method' => 'PATCH','route' => ['pTicketCalculationCRUD.update', $ticket->id]]) !!}

    <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}                 

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car number:</strong>

                {!! Form::text('carNumber', null, array('placeholder' => 'Car number','class' => 'form-control','style'=>'height:100px')) !!}

            </div>

        </div>

          <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car Model:</strong>

                 {{ Form::select('carModel', ['','Maruti'=>'Maruti','Toyota'=>'Toyota', 'Hyundai'=>'Hyundai'] ) }}

            </div>

        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Fare per Day:</strong>

               

                {{ Form::radio('farePerDay', '10') }}10
                {{ Form::radio('farePerDay', '20') }}20
                {{ Form::radio('farePerDay', '5') }}5

               

            </div>

        </div>

           <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>No of days:</strong>


                   {!! Form::checkbox('noOfDays', '3',$ticket->noOfDays==='3') !!} 3
                   {!! Form::checkbox('noOfDays', '4',$ticket->noOfDays==='4') !!} 4

            </div>

        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>No of cars:</strong>

                 <?php $noOfCars = array(3=>"3",4=>"4",5=>"5",6=>"6");
                        $selectedCars = explode(',', $ticket->noOfCars);
                 ?>

                {{ Form::select('noOfCars[]', $noOfCars, $selectedCars,array('multiple')) }} 

            </div>

        </div>



        <div class="col-xs-12 col-sm-12 col-md-12 text-center">

                <button type="submit" class="btn btn-primary">Submit</button>

        </div>


    </div>

    {!! Form::close() !!}


@endsection