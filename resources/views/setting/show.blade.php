@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
            Category
        </h5>
                <div class="row" style="padding-left: 20px">

                    @include('category.show_fields')
                    <div class="col-md-12 my-4">
                    <a href="{{ route('translate.index') }}" class="btn btn-purple ">Back</a></div>
                </div>
            </div>
        </div>
</section>
@endsection
