@extends('_layouts.application')

@section('head')
    <!-- Bootstrap Select Css -->
    <link href="/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <link href="/plugins/js-year-calendar/js-year-calendar.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Input -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Dienst anlegen
                    </h2>
                </div>
                <div class="body">
                    @include('service._form')
                </div>
            </div>
        </div>
        <!-- #END# Input -->
    </div>
@endsection
