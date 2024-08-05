@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Notifications</h1><br>

            <div class="row mb-4 d-flex justify-content-center">
                <div class="col-5">
                    <form method="GET" action="{{ route('profile.notifications') }}" class="row-12 mb-4 d-flex justify-content-center">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="rental" {{ request('type') === 'rental' ? 'selected' : '' }}>Rental</option>
                            <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Payment</option>
                            <option value="penalty" {{ request('type') === 'penalty' ? 'selected' : '' }}>Penalty Payment</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>

            @if ($notifications->isEmpty())
                <br>
                <h4 class="text-center">No notifications yet.</h4>
            @else

            @foreach ($notifications as $notification)
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if ($notification->type === 'rental')
                                <span class="badge text-bg-primary">Rental</span>
                            @elseif ($notification->type === 'payment')
                                <span class="badge text-bg-primary">Payment</span>
                            @else
                                <span class="badge text-bg-primary">Penalty Payment</span>
                            @endif
                            <strong>{{ $notification->title }}</strong>
                            <div>
                                <small>{{ $notification->created_at->addHour(2) }}</small>
                            </div>
                        </div>
                        <div>
                            @unless ($notification->status === 'read')
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Mark as Read</button>
                                </form>
                            @endunless
                            @if ($notification->status === 'read')
                            <form action="{{ route('notifications.destroy', $notification->id) }}" class="d-inline delete-form" id="delete-form-{{ $notification->id }}">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $notification->id }})">Delete</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="accordion mt-2" id="accordion{{ $notification->id }}">
                        <div class="accordion-item alert">
                            <h2 class="accordion-header" id="heading{{ $notification->id }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $notification->id }}" aria-expanded="true" aria-controls="collapse{{ $notification->id }}" style="background-color: #66B2FF">
                                    <strong>View Details</strong>
                                </button>
                            </h2>
                            <div id="collapse{{ $notification->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $notification->id }}" data-bs-parent="#accordion{{ $notification->id }}">
                                <div class="accordion-body">
                                    @if ($notification->type === 'rental')
                                        {!! nl2br(e($notification->message)) !!}
                                    @elseif ($notification->type === 'payment')
                                        {!! nl2br(e($notification->message)) !!}
                                    @else
                                        {!! nl2br(e($notification->message)) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{ $notifications->links() }}
    </div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Deletion</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to delete this notification?</p>
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
