@extends('layouts.default')


@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Create New Item</h2>

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


    {!! Form::open(array('route' => 'pTicketCalculationCRUD.store','method'=>'POST')) !!}

     <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car Number:</strong>

                {!! Form::text('carNumber', null, array('placeholder' => 'Car Number','class' => 'form-control')) !!}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car Model:</strong>

               {{ Form::select('carModel', ['','Maruti'=>'Maruti','Toyota'=>'Toyota', 'Hyundai'=>'Hyundai'],old('carModel') ) }}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
        
         <strong>Fare per day:</strong>
         
            {{ Form::radio('farePerDay', '10') }}10
            {{ Form::radio('farePerDay', '20') }}20
            {{ Form::radio('farePerDay', '5') }}5


            
         </div>

        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>No of days:</strong>
                {!! Form::checkbox('noOfDays','3'),'noOfDays'==='3' !!} 3

               {!! Form::checkbox('noOfDays','4'), 'noOfDays'==='4' !!} 4

            
          



            </div>

        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>No of cars:</strong>
                <?php $noOfCars = array(3=>"3",4=>"4",5=>"5",6=>"6")?>

                {{ Form::select('noOfCars[]', $noOfCars, null, array('multiple'),old('noOfCars')) }} 

            </div>

        </div>





        <div class="col-xs-12 col-sm-12 col-md-12 text-center">

                <button type="submit" class="btn btn-primary">Submit</button>

        </div>


    </div>

    {!! Form::close() !!}


@endsection