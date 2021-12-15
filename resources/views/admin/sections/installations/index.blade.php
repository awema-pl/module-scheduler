@extends('indigo-layout::installation')

@section('meta_title', _p('scheduler::pages.admin.installation.meta_title', 'Installation module Scheduler') . ' - ' . config('app.name'))
@section('meta_description', _p('scheduler::pages.admin.installation.meta_description', 'Installation module Scheduler in application'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('scheduler::pages.admin.installation.headline', 'Installation module Scheduler') }}</h2>
@endsection

@section('content')
    <form-builder disabled-dialog="" url="{{ route('scheduler.admin.installation.index') }}" send-text="{{ _p('scheduler::pages.admin.installation.send_text', 'Install') }}"
    edited>
        <div class="section">
            <div class="section">
                {{ _p('scheduler::pages.admin.installation.will_be_execute_migrations', 'Will be execute package migrations') }}
            </div>
        </div>
    </form-builder>
@endsection
