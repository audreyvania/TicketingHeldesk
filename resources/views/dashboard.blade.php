@extends('layouts.app')

@section('content')
    <div class="py-4 py-md-5">
        <div class="container">
            <div class="mb-4 d-flex flex-column gap-3 flex-lg-row align-items-lg-start justify-content-lg-between">
                <div>
                    <h1 class="h3 mb-1 fw-semibold theme-page-title">Dashboard</h1>
                    <div class="theme-accent-line mb-2"></div>
                    <p class="mb-0 text-secondary">Track the status of tickets you have submitted.</p>
                </div>

                <a href="{{ route('tickets.create') }}" class="instant-nav btn btn-accent">
                    Create Ticket
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="summary-card bg-success text-white p-4 rounded col-12 col-md-3">
                    Open <br>
                    <b>{{ $open ?? 0 }}</b>
                </div>

                <div class="summary-card bg-warning text-white p-4 rounded col-12 col-md-3">
                    On Progress <br>
                    <b>{{ $progress ?? 0 }}</b>
                </div>

                <div class="summary-card bg-primary text-white p-4 rounded col-12 col-md-3">
                    Resolved <br>
                    <b>{{ $resolved ?? 0 }}</b>
                </div>

                <div class="summary-card bg-secondary text-white p-4 rounded col-12 col-md-3">
                    Closed <br>
                    <b>{{ $closed ?? 0 }}</b>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-column gap-1 flex-sm-row justify-content-sm-between">
                    <h2 class="h5 mb-0 fw-semibold">My Tickets</h2>
                    <a href="{{ route('tickets.my') }}" class="instant-nav small text-decoration-none">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No Ticket</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Progress</th>
                                    <th>Latest History</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets ?? [] as $ticket)
                                    @php
                                        $statusClass = match ($ticket->status) {
                                            'Open' => 'text-bg-primary',
                                            'On Progress' => 'text-bg-warning',
                                            'Resolved' => 'text-bg-info',
                                            'Closed' => 'text-bg-success',
                                            default => 'text-bg-secondary',
                                        };
                                        $progressPercent = match ($ticket->status) {
                                            'Open' => 25,
                                            'On Progress' => 50,
                                            'Resolved' => 75,
                                            'Closed' => 100,
                                            default => 0,
                                        };
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $ticket->ticket_no }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ $ticket->category->name ?? '-' }}</td>
                                        <td style="min-width: 220px;">
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                <span class="badge {{ $statusClass }}">{{ $ticket->status }}</span>
                                                <span class="small text-secondary">{{ $progressPercent }}%</span>
                                            </div>
                                            <div class="progress ticket-progress" role="progressbar" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $ticket->latestLog->note ?? 'No history yet.' }}</div>
                                            @if ($ticket->latestLog)
                                                <div class="small text-secondary">
                                                    {{ $ticket->latestLog->user->name ?? 'IT Support' }} · {{ $ticket->latestLog->created_at->format('d M Y H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="instant-nav btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-secondary">
                                            No tickets yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
