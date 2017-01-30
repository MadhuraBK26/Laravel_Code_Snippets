@extends('layouts.default')

 

@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2> Show Parking Details</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('pTicketCalculationCRUD.index') }}"> Back</a>

            </div>

        </div>

    </div>


    <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {{ $ticket->name }}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car Number:</strong>

                {{ $ticket->carNumber }}

            </div>

        </div>


          <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Car Model:</strong>

                {{ $ticket->carModel }}

            </div>

        </div>


          <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Fare per Day:</strong>

                {{ $ticket->farePerDay }}

            </div>

        </div>

          
           <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>No of Days:</strong>

                {{ $ticket->noOfDays }}

            </div>

        </div>

          <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Number of cars:</strong>

                {{ $ticket->noOfCars }}

            </div>

        </div>

     </div>


@endsection