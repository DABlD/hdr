@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box-archive mr-1"></i>
                            Packages Sold
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Search name (if blank all)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label for="from">From</label>
                                <input type="text" id="from" class="form-control" value="{{ now()->subDays('7')->format("Y-m-d") }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to">To</label>
                                <input type="text" id="to" class="form-control" value="{{ now()->format("Y-m-d") }}">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills ml-auto" style="padding-left: revert;" style="text-align: center;">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#data_analysis" data-toggle="tab" data-href="data_analysis">Data Analysis</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                                <a class="nav-link" href="#Results" data-toggle="tab" data-href="Results">Results</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                                <a class="nav-link" href="#classification" data-toggle="tab" data-href="classification">Classification</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                        </ul>

                                        <br>

                                        <div class="tab-content p-0">
                                            <div class="chart tab-pane active" id="data_analysis" style="position: relative;"></div>
                                            <div class="chart tab-pane" id="clinic_history" style="position: relative;"></div>
                                            <div class="chart tab-pane" id="classification" style="position: relative;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        table{
            margin-top: 30px;
        }

        .table-container{
            height: 500px;
            width: 100%;
            overflow-y: scroll;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/charts.min.js') }}"></script>
    <script src="{{ asset('js/numeral.min.js') }}"></script>

    <script>
    </script>
@endpush