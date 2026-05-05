@extends('layouts.app')

@section('content')
    <div class="py-4 py-md-5">
        <div class="container">
            <div class="mb-4 d-flex flex-column gap-3 flex-lg-row align-items-lg-start justify-content-lg-between">
                <div>
                    <h1 class="h3 mb-1 fw-semibold theme-page-title">Manage Tickets</h1>
                    <div class="theme-accent-line mb-2"></div>
                    <p class="mb-0 text-secondary">View all tickets and filter by status, date, or category.</p>
                </div>

                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-accent">
                    Back to Dashboard
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 mb-0 fw-semibold">Filter Ticket</h2>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('tickets.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-medium">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    @foreach(['Open', 'On Progress', 'Resolved', 'Closed'] as $status)
                                        <option value="{{ $status }}" @selected(request('status') === $status)>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="category" class="form-label fw-medium">Category</label>
                                <select id="category" name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected((string) request('category') === (string) $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="date" class="form-label fw-medium">Created Date</label>
                                <input id="date" type="date" name="date" value="{{ request('date') }}" class="form-control">
                            </div>

                            <div class="col-md-1 d-grid">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>

                        @if(request()->hasAny(['status', 'category', 'date']))
                            <div class="mt-3">
                                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border">
                                    Reset Filter
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex flex-column gap-1 flex-sm-row justify-content-sm-between">
                    <h2 class="h5 mb-0 fw-semibold">All Tickets</h2>
                    <span class="text-secondary small">{{ $tickets->count() }} tickets found</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No Ticket</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Category</th>
                                    <th>Requester</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    @php
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
                                        <td>{{ $ticket->title }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ $ticket->status }}</span></td>
                                        <td>{{ $ticket->category->name ?? '-' }}</td>
                                        <td>{{ $ticket->user->name ?? 'Requester' }}</td>
                                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-accent">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 text-center text-secondary">
                                            No tickets match the selected filters.
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
