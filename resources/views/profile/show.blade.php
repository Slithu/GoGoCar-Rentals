@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-start min-vh-100">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="card-title mb-4" style="font-size: 2.5rem;">My Profile</h1>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($users as $user)
                        <div class="mb-4 text-left">
                            @if ($user->image_path)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $user->image_path) }}" alt="Profile Photo" class="img-thumbnail rounded-circle" style="max-width: 150px; max-height: 150px;">
                                </div>
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Profile Photo" class="img-thumbnail rounded-circle" style="max-width: 150px; max-height: 150px;">
                            @endif
                            @if(empty($user->image_path))
                                <br><br>
                            @endif
                            <h5 class="card-title" style="font-size: 1.5rem;">{{ $user->name }} {{ $user->surname }}</h5>
                            <p class="card-text" style="font-size: 1.2rem;">
                                <strong>Sex:</strong> {{ $user->sex }} <br>
                                <strong>Email:</strong> {{ $user->email }} <br>
                                <strong>Phone:</strong> {{ $user->phone }} <br>
                                <strong>License:</strong> {{ $user->license }} <br>
                                <strong>Birth:</strong> {{ $user->birth }} <br>
                                <strong>Location:</strong> {{ $user->town }}, {{ $user->zip_code }}, {{ $user->country }}
                            </p>

                            <div class="text-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-md">Edit Profile</a>
                                <form method="POST" action="{{ route('profile.create') }}" enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fas fa-camera"></i> Add Profile Photo
                                    </button>
                                </form>
                                @can('isUser')
                                    <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-md">Delete Account</a>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
