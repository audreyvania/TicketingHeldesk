@extends('layouts.app')

@section('content')
    <div class="py-4 py-md-5">
        <div class="container">
            <div class="mb-4 d-flex flex-column gap-3 flex-lg-row align-items-lg-start justify-content-lg-between">
                <div>
                    <h1 class="h3 mb-1 fw-semibold theme-page-title">IT Support Dashboard</h1>
                    <div class="theme-accent-line mb-2"></div>
                    <p class="mb-0 text-secondary">Monitor incoming tickets and continue support based on their status.</p>
                </div>

                <a href="{{ route('tickets.index') }}" class="instant-nav btn btn-accent">
                    Manage All Tickets
                </a>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="summary-card bg-success text-white p-4 rounded">
                        Open <br>
                        <b>{{ $open ?? 0 }}</b>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="summary-card bg-warning text-white p-4 rounded">
                        On Progress <br>
                        <b>{{ $progress ?? 0 }}</b>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="summary-card bg-info text-white p-4 rounded">
                        Resolved <br>
                        <b>{{ $resolved ?? 0 }}</b>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="summary-card bg-secondary text-white p-4 rounded">
                        Closed <br>
                        <b>{{ $closed ?? 0 }}</b>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-column gap-1 flex-sm-row justify-content-sm-between">
                    <h2 class="h5 mb-0 fw-semibold">Latest Tickets</h2>
                    <a href="{{ route('tickets.index') }}" class="instant-nav small text-decoration-none">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No Ticket</th>
                                    <th>Requester</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    /** @var \Illuminate\Support\Collection|\App\Models\Ticket[] $tickets */
                                    $tickets = $tickets ?? [];
                                @endphp

                                @if(count($tickets))
                                    @foreach($tickets as $ticket)
                                        @php
                                            /** @var \App\Models\Ticket $ticket */
                                            $statusClass = match ($ticket->status) {
                                                'Open' => 'text-bg-primary',
                                                'On Progress' => 'text-bg-warning',
                                                'Resolved' => 'text-bg-info',
                                                'Closed' => 'text-bg-success',
                                                default => 'text-bg-secondary',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $ticket->ticket_no }}</td>
                                            <td>{{ $ticket->user->name ?? 'Requester' }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ $ticket->category->name ?? '-' }}</td>
                                            <td><span class="badge {{ $statusClass }}">{{ $ticket->status }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="instant-nav btn btn-sm btn-accent">
                                                    Manage
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-secondary">
                                            No tickets yet.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection