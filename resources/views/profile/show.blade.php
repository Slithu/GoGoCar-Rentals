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
                            <div class="position-relative d-inline-block">
                                @if ($user->image_path)
                                    <img src="{{ asset('storage/' . $user->image_path) }}" alt="Profile Photo" class="img-thumbnail rounded-circle profile-photo" style="max-width: 150px; max-height: 150px;">
                                    <div class="overlay" onclick="confirmDeleteImage({{ $user->id }})">
                                        <span>Click to Remove Profile Photo</span>
                                    </div>
                                @else
                                    <form action="{{ route('profile.create') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0">
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Profile Photo" class="img-thumbnail rounded-circle" style="max-width: 150px; max-height: 150px;">
                                            <div class="overlay">
                                                <span>Click to Add Profile Photo</span>
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            </div>

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
                                @if (!empty($user->image_path))
                                    <a href="{{ route('profile.create') }}" class="btn btn-primary btn-md">Change Profile Photo</a>
                                @endif

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

<!-- Modal for Image Deletion Confirmation -->
<div id="deleteImageModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Image Deletion</h5>
            <span class="custom-modal-close" onclick="closeImageModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete your profile photo?</p>
        </div>
        <div class="custom-modal-footer gap-3">
            <form id="deleteImageForm" action="{{ route('profile.removeImage', $user->id) }}">
                <input type="hidden" name="user_id" id="deleteImageUserId" value="">
                <button type="submit" class="btn btn-danger">Delete Photo</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="closeImageModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Modal for Account Deletion Confirmation -->
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

    .profile-photo {
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.9rem;
        padding: 5px;
        border-radius: 8px;
        cursor: pointer;
    }

    .position-relative:hover .overlay {
        opacity: 1;
    }
</style>

<script>
    let deleteImageForm = null;

    function confirmDeleteImage(userId) {
        document.getElementById('deleteImageUserId').value = userId;
        document.getElementById('deleteImageModal').style.display = 'block';
    }

    function closeImageModal() {
        document.getElementById('deleteImageModal').style.display = 'none';
    }

    function confirmDelete(userId) {
        deleteFormId = 'delete-form-' + userId;
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
