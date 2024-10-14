@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $users }}</h3>
                        <p>Total Patients</p>
                    </div>

                    <div class="icon">
                        <i class="nav-icon fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $companies }}</h3>
                        <p>Total Companies</p>
                    </div>
                    <div class="icon">
                        <i class="nav-icon fas fa-buildings"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $ppcount }}</h3>
                        <p>Packages Sold</p>
                    </div>

                    <div class="icon">
                        <i class="nav-icon fas fa-cubes"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>₱{{ number_format($total, 2) }}</h3>
                        <p>Total Sold</p>
                    </div>
                    <div class="icon">
                        <i class="nav-icon fas fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-6 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Packages Sold
                        </h3>
                    </div>

                    <div class="card-body">
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

                        <div class="row">
                            <canvas id="report" width="100%"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-lg-6 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-dollar mr-1"></i>
                            Sales
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="from2">From</label>
                                <input type="text" id="from2" class="form-control" value="{{ now()->subDays('7')->format("Y-m-d") }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to2">To</label>
                                <input type="text" id="to2" class="form-control" value="{{ now()->format("Y-m-d") }}">
                            </div>
                        </div>

                        <div class="row">
                            <canvas id="report2" width="100%"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/charts.min.js') }}"></script>

    <script>
        var from = moment().subtract(7, 'days').format(dateFormat);
        var to = dateNow();
        var from2 = moment().subtract(7, 'days').format(dateFormat);
        var to2 = dateNow();
        var ctx, myChart, ctx2, myChart2;

        let settings = {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        };

        $(document).ready(() => {
            $("#from, #from2").flatpickr(settings);
            $("#to, #to2").flatpickr(settings);
            createChart1();
            createChart2();
        });

        $("#from").on('change', e => {
            from = e.target.value;
            myChart.destroy();
            createChart();
        });

        $("#to").on('change', e => {
            to = e.target.value;
            myChart.destroy();
            createChart1();
        });

        $("#from2").on('change', e => {
            from2 = e.target.value;
            myChart2.destroy();
            createChart2();
        });

        $("#to").on('change', e => {
            to = e.target.value;
            myChart.destroy();
            createChart1();
        });

        $("#to2").on('change', e => {
            to2 = e.target.value;
            myChart2.destroy();
            createChart2();
        });

        function createChart1(){
            $.ajax({
                url: "{{ route("getReport1") }}",
                data:{
                    from: from,
                    to: to
                },
                success: result => {
                    result = JSON.parse(result);

                    ctx = document.getElementById('report').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.dataset
                        }
                    });
                }
            });
        }

        function createChart2(){
            $.ajax({
                url: "{{ route("getReport2") }}",
                data:{
                    from: from2,
                    to: to2
                },
                success: result => {
                    result = JSON.parse(result);

                    ctx = document.getElementById('report2').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.dataset
                        }
                    });
                }
            });
        }
    </script>
@endpush