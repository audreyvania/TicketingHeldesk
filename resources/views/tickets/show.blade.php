@extends('layouts.app')

@section('content')
    @php
        $statusClass = match ($ticket->status) {
            'Open' => 'text-bg-primary',
            'On Progress' => 'text-bg-warning',
            'Resolved' => 'text-bg-info',
            'Closed' => 'text-bg-success',
            default => 'text-bg-secondary',
        };
    @endphp

    <div class="ticket-detail-page pb-4 pb-md-5">
        <div class="container">
            <div class="mb-4 d-flex flex-column gap-3 flex-lg-row align-items-lg-start justify-content-lg-between">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill {{ $statusClass }}">{{ $ticket->status }}</span>
                        <span class="text-secondary fw-medium">{{ $ticket->ticket_no }}</span>
                    </div>
                    <h1 class="h3 mb-2 fw-semibold theme-page-title">{{ $ticket->title }}</h1>
                    <div class="theme-accent-line"></div>
                </div>

                <a href="{{ $backUrl }}"
                   class="btn btn-outline-accent">
                    Back
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-2 fw-semibold">The status could not be updated.</p>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h2 class="h5 mb-0 fw-semibold">Detail Ticket</h2>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-secondary mb-4" style="white-space: pre-line;">{{ $ticket->description }}</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border">
                                        <div class="small text-secondary">Category</div>
                                        <div class="fw-semibold">{{ $ticket->category->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border">
                                        <div class="small text-secondary">Requested By</div>
                                        <div class="fw-semibold">{{ $ticket->user->name ?? 'Requester' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border">
                                        <div class="small text-secondary">Created Date</div>
                                        <div class="fw-semibold">{{ $ticket->created_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light border">
                                        <div class="small text-secondary">Last Updated</div>
                                        <div class="fw-semibold">{{ $ticket->updated_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h2 class="h5 mb-0 fw-semibold">History Ticket</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($logs as $log)
                                    @php
                                        $logClass = match ($log->status) {
                                            'Open' => 'text-bg-primary',
                                            'On Progress' => 'text-bg-warning',
                                            'Resolved' => 'text-bg-info',
                                            'Closed' => 'text-bg-success',
                                            default => 'text-bg-secondary',
                                        };
                                    @endphp
                                    <div class="list-group-item p-4">
                                        <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-between">
                                            <div>
                                                <span class="badge {{ $logClass }} mb-2">{{ $log->status }}</span>
                                                <p class="mb-1">{{ $log->note ?? 'No notes provided.' }}</p>
                                                <div class="small text-secondary">
                                                    {{ $log->user->name ?? 'IT Support' }}
                                                </div>
                                            </div>
                                            <div class="small text-secondary">
                                                {{ $log->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-secondary">No history yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h2 class="h5 mb-0 fw-semibold">Status</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="small text-secondary mb-1">Current Status</div>
                                <span class="badge fs-6 {{ $statusClass }}">{{ $ticket->status }}</span>
                            </div>

                            @if (auth()->user()->role === 'it')
                                @if ($nextStatus)
                                    <form method="POST" action="{{ route('tickets.updateStatus', $ticket->id) }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="status" class="form-label fw-medium">Update Status</label>
                                            <select id="status" name="status" class="form-select" required>
                                                <option value="{{ $nextStatus }}" selected>
                                                    {{ $ticket->status }} to {{ $nextStatus }}
                                                </option>
                                            </select>
                                            <div class="form-text">
                                                Status flow: Open to On Progress, On Progress to Resolved, Resolved to Closed.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="note" class="form-label fw-medium">Note</label>
                                            <textarea
                                                id="note"
                                                name="note"
                                                class="form-control"
                                                rows="4"
                                                placeholder="Example: Checking with the requester"
                                                required
                                            >{{ old('note') }}</textarea>
                                        </div>

                                        <button type="submit" class="btn btn-accent w-100">Update Status</button>
                                    </form>
                                @else
                                    <div class="alert alert-success mb-0">
                                        This ticket is already Closed. No further status updates are available.
                                    </div>
                                @endif
                            @else
                                <p class="mb-0 text-secondary">
                                    The status will be updated by the IT team as the ticket is processed.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
