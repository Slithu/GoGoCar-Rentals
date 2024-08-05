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
                                        <i class="fas fa-camera"></i>Change Profile Photo
                                    </button>
                                </form>
                                @can('isUser')
                                <form action="{{ route('users.destroy', $user->id) }}" class="d-inline delete-form" id="delete-form-{{ $user->id }}">
                                    <button type="button" class="btn btn-danger btn-md" onclick="confirmDelete({{ $user->id }})">Delete Account</button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete your account?</p>
        </div>
        <div class="custom-modal-footer gap-3">
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 40%;
        max-width: 500px;
        border-radius: 8px;
    }

    .custom-modal-header, .custom-modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .custom-modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .custom-modal-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-modal-close:hover,
    .custom-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .custom-modal-body {
        text-align: center;
        margin: 20px 0;
    }
</style>

<script>
    let deleteFormId = null;

    function confirmDelete(carId) {
        deleteFormId = 'delete-form-' + carId;
        document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
        closeModal();
    });
</script>

@endsection
