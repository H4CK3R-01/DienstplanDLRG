@extends('_layouts.application')

@section('head')
@endsection

@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Abwesenheit bearbeiten
                </h2>
            </div>
            <div class="body">
                @include('holiday._form')
            </div>
        </div>
    </div>
@endsection
