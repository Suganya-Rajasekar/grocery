@extends('layouts.app')

@section('content')
    <div class="content">
        @include('flash::message')
        <div class="pvr-wrapper">
            <div class="pvr-box">
                    @include('service.table')
            </div>
        </div>
    </div>
@endsection

