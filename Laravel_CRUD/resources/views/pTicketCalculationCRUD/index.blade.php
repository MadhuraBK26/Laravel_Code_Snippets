@extends('layouts.default')

 

@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Car Parking CRUD</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('pTicketCalculationCRUD.create') }}"> Create New Item</a>

              


            </div>

        </div>

    </div>


   @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif


    <table class="table table-bordered">

        <tr>

            

            <th>Name</th>

            <th>Car number</th>

            <th>Car model</th>

            <th>Fare per day</th>

            <th>Number of days</th>

            <th>Number of cars</th>

            <th width="280px">Action</th>

        </tr>

    @foreach ($tickets as $key => $item)

    <tr>

       

        <td>{{ $item->name }}</td>

        <td>{{ $item->carNumber }}</td>

        <td>{{ $item->carModel }}</td>

        <td>{{ $item->farePerDay }}</td>

        <td>{{ $item->noOfDays }}</td>
   
        <td>{{ $item->noOfCars }}</td>

        <td>

            <a class="btn btn-info" href="{{ route('pTicketCalculationCRUD.show',$item->id) }}">Show</a>

            <a class="btn btn-primary" href="{{ route('pTicketCalculationCRUD.edit',$item->id) }}">Edit</a>

            {!! Form::open(['method' => 'DELETE','route' => ['pTicketCalculationCRUD.destroy', $item->id],'style'=>'display:inline']) !!}

            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

            {!! Form::close() !!}

        </td>

    </tr>

    @endforeach

    </table>


    {!! $tickets->render() !!}


@endsection
