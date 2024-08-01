@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Notifications</h1><br>
            @if ($notifications->isEmpty())
                <p>No notifications yet.</p>
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
                                <a href="{{ route('notifications.destroy', $notification->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
@endsection
