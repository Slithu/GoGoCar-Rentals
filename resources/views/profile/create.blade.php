@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Add Photo to User Profile</div>

                <div class="card-body">
                    <form id="profilePhotoForm" method="POST" action="{{ route('profile.addProfilePhoto') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">Image</label>

                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                <span id="error-message" class="text-danger"></span>

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profilePhotoForm').addEventListener('submit', function(event) {
        const fileInput = document.getElementById('image');
        const errorMessage = document.getElementById('error-message');
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!fileInput.files.length) {
            errorMessage.textContent = 'Please select an image file.';
            event.preventDefault();
            return;
        }

        const file = fileInput.files[0];
        if (!allowedTypes.includes(file.type)) {
            errorMessage.textContent = 'The file must be an image (jpeg, png, jpg).';
            event.preventDefault();
            return;
        }

        errorMessage.textContent = '';
    });
</script>
@endsection
