@extends('layouts.app')

@section('content')
    <div class="py-4 py-md-5">
        <div class="container" style="max-width: 860px;">
            <div class="mb-4 d-flex flex-column gap-3 flex-sm-row align-items-sm-center justify-content-sm-between">
                <div>
                    <h1 class="h3 mb-1 fw-semibold theme-page-title">Create Ticket</h1>
                    <div class="theme-accent-line mb-2"></div>
                    <p class="mb-0 text-secondary">
                        Submit issue details so the IT team can follow up accurately.
                    </p>
                </div>

                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-outline-accent"
                >
                    Back
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h5 mb-0 fw-semibold">Informasi Ticket</h2>
                </div>

                <form method="POST" action="{{ route('tickets.store') }}" class="card-body p-4 p-md-5">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-2 fw-semibold">The ticket could not be submitted.</p>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="title" class="form-label fw-medium">
                            Title
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control form-control-lg"
                            placeholder="Example: Laptop cannot connect to WiFi"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-medium">
                            Category
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select form-select-lg"
                            required
                        >
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-medium">
                            Description
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="6"
                            placeholder="Describe the issue, location, device, and steps already tried."
                            required
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex flex-column-reverse flex-sm-row gap-2 justify-content-end border-top pt-4">
                        <a
                            href="{{ route('dashboard') }}"
                            class="btn btn-light border"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="btn btn-accent px-4"
                        >
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
