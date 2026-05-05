@extends('layouts.app')

@section('content')
    <div class="py-4 py-md-5">
        <div class="container" style="max-width: 960px;">
            <div class="mb-4">
                <h1 class="h3 mb-1 fw-semibold theme-page-title">{{ __('Profile') }}</h1>
                <div class="theme-accent-line mb-2"></div>
                <p class="mb-0 text-secondary">Manage your account information and password security.</p>
            </div>

            <div class="profile-card p-4 p-md-5 mb-4">
                <div style="max-width: 36rem;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="profile-card p-4 p-md-5 mb-4">
                <div style="max-width: 36rem;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-card p-4 p-md-5">
                <div style="max-width: 36rem;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
