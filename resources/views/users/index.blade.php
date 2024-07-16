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
            <h1>Users List</h1>
        </div>
    </div>
    <br>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Surname</th>
            <th scope="col">Sex</th>
            <th scope="col">Email</th>
            <th scope="col">Phone Number</th>
            <th scope="col">Driver's license number</th>
            <th scope="col">Date of birth</th>
            <th scope="col">Town</th>
            <th scope="col">Zip code</th>
            <th scope="col">Country</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->surname}}</td>
                    <td>{{$user->sex}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->license}}</td>
                    <td>{{$user->birth}}</td>
                    <td>{{$user->town}}</td>
                    <td>{{$user->zip_code}}</td>
                    <td>{{$user->country}}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <a href="{{ route('users.destroy', $user->id) }}">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-absolute bottom-0 start-50 translate-middle-x">
        {{$users->links()}}
    </div>
</div>
@endsection
