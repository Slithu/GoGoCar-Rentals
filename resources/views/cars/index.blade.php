@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        <div class="col-10">
            <h1>Cars Fleet</h1>
        </div>
        @can('isAdmin')
            <div class="col-2">
                <a class="float-right" href=" {{ route('cars.create') }} ">
                    <button type="button" class="btn btn-primary">Create a new Car</button>
                </a>
            </div>
        @endcan
    </div><br><br>
    <br>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Brand</th>
            <th scope="col">Model</th>
            <th scope="col">Car Body</th>
            <th scope="col">Engine Type</th>
            <th scope="col">Transmission</th>
            <th scope="col">Engine Power</th>
            <th scope="col">Seats</th>
            <th scope="col">Doors</th>
            <th scope="col">Suitcases</th>
            <th scope="col">Price</th>
            <th scope="col">Availability</th>
            <th scope="col">Description</th>
            @can('isAdmin')
            <th scope="col">Actions</th>
            @endcan
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>
                    <th scope="row">{{$car->id}}</th>
                    <td>{{$car->brand}}</td>
                    <td>{{$car->model}}</td>
                    <td>{{$car->car_body}}</td>
                    <td>{{$car->engine_type}}</td>
                    <td>{{$car->transmission}}</td>
                    <td>{{$car->engine_power}}</td>
                    <td>{{$car->seats}}</td>
                    <td>{{$car->doors}}</td>
                    <td>{{$car->suitcases}}</td>
                    <td>{{$car->price}}</td>
                    <td>{{$car->availability->available ? 'Available' : 'Not Available' }}</td>
                    <td>{{$car->description}}</td>
                    @can('isAdmin')
                    <td>
                        <a href="{{ route('cars.show', $car->id) }}">
                            <button class="btn btn-success btn-sm">Show</button>
                        </a>
                        <a href="{{ route('cars.edit', $car->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <a href="{{ route('cars.destroy', $car->id) }}">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </a>
                    </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$cars->links()}}
    </div>
</div>
@endsection
