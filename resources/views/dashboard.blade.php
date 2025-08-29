@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
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

            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $companies->count() }}</h3>
                        <p>Total Companies</p>
                    </div>
                    <div class="icon">
                        <i class="nav-icon fas fa-buildings"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
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

            {{-- <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>₱{{ number_format($total, 2) }}</h3>
                        <p>Total Sold</p>
                    </div>
                    <div class="icon">
                        <i class="nav-icon fas fa-sack-dollar"></i>
                    </div>
                </div>
            </div> --}}
        </div>

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
                            <div class="col-md-3">
                                <label for="from">From</label>
                                <input type="text" id="from" class="form-control" value="{{ now()->subDays('7')->format("Y-m-d") }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to">To</label>
                                <input type="text" id="to" class="form-control" value="{{ now()->format("Y-m-d") }}">
                            </div>
                            <div class="col-md-3">
                                <label for="company">Company</label>
                                <select class="form-control" id="company" style="margin-bottom: 0px;">
                                    <option value="%%">All</option>
                                    @foreach($companies as $company)
                                        <option value="{!! $company->fname !!}">{!! $company->fname !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" style="text-align: right; margin: auto;">
                                <br>
                                <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Export" onclick="exportExcel1()">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <canvas id="report" width="100%"></canvas>

                            <div class="table-container">
                                <table class="table table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Company</th>
                                            <th>Package</th>
                                            {{-- <th>Amount</th> --}}
                                            <th>Type</th>
                                        </tr>
                                        <tbody id="table1"></tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>  

            {{-- <section class="col-lg-6 connectedSortable">
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
                            <div class="col-md-3">
                                <label for="company2">Company</label>
                                <select class="form-control" id="company2" style="margin-bottom: 0px;">
                                    <option value="%%">All</option>
                                    @foreach($companies as $company)
                                        <option value="{!! $company->fname !!}">{!! $company->fname !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" style="text-align: right; margin: auto;">
                                <br>
                                <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Export" onclick="exportExcel2()">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <canvas id="report2" width="100%"></canvas>

                            <div class="table-container">
                                <table class="table table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        <tbody id="table2"></tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}

        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">

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
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/charts.min.js') }}"></script>
    <script src="{{ asset('js/numeral.min.js') }}"></script>

    <script>
        var from = moment().subtract(7, 'days').format(dateFormat);
        var to = dateNow();
        var company = "%%";
        var from2 = moment().subtract(7, 'days').format(dateFormat);
        var to2 = dateNow();
        var company2 = "%%";
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
            createChart1();
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

        $("#to2").on('change', e => {
            to2 = e.target.value;
            myChart2.destroy();
            createChart2();
        });

        $("#company").on('change', e => {
            company = e.target.value;
            myChart.destroy();
            createChart1();
        });

        $("#company2").on('change', e => {
            company2 = e.target.value;
            myChart2.destroy();
            createChart2();
        });

        function createChart1(){
            $.ajax({
                url: "{{ route("getReport1") }}",
                data:{
                    from: from,
                    to: to,
                    company: company
                },
                success: result => {
                    let chart = JSON.parse(result)["chart"];
                    let table = JSON.parse(result)["table"];

                    ctx = document.getElementById('report').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chart.labels,
                            datasets: chart.dataset
                        },
                        options: {
                            scales: {
                                y: {
                                    min: 0,              // ✅ minimum always 0
                                    ticks: {
                                        stepSize: 1        // ✅ increments of 1
                                    }
                                }
                            }
                        }
                    });

                    let tableString = "";

                    table.forEach((row, index) => {
                        tableString = `
                            <tr>
                                <td>${index+1}</td>
                                <td>${row.package.company}</td>
                                <td>${row.package.name}</td>
                                {{-- <td>₱${numeral(row.package.amount).format("0,0")}</td> --}}
                                <td>${row.type == "PEE" ? "PPE" : row.type}</td>
                            </tr>
                        ` + tableString;
                    });

                    $('#table1').html(tableString);
                }
            });
        }

        function exportExcel1(){
            let data = {
                from: from,
                to: to,
                company: company
            };

            window.location.href = "{{ route('report.packagesSold') }}?" + $.param(data);
        }

        function createChart2(){};

        function deltedcreateChart2(){
            $.ajax({
                url: "{{ route("getReport2") }}",
                data:{
                    from: from2,
                    to: to2,
                    company: company2
                },
                success: result => {
                    let chart = JSON.parse(result)["chart"];
                    let table = JSON.parse(result)["table"];

                    ctx = document.getElementById('report2').getContext('2d');
                    myChart2 = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chart.labels,
                            datasets: chart.dataset
                        }
                    });

                    let tableString = "";
                    let total = 0;

                    chart.labels.forEach((label, index) => {
                        total += chart.dataset[0].data[[index]];

                        tableString = `
                            <tr>
                                <td>${index+1}</td>
                                <td>${label}</td>
                                {{-- <td>₱${numeral(chart.dataset[0].data[[index]]).format("0,0.00")}</td> --}}
                                <td>${chart.dataset[0].data[[index]]}</td>
                            </tr>
                        ` + tableString;
                    });

                    tableString = `
                        <tr>
                            <td></td>
                            <td>Total</td>
                            {{-- <td>₱${numeral(total).format("0,0.00")}</td> --}}
                            <td>${total}</td>
                        </tr>
                        ${tableString}
                    `;

                    $('#table2').html(tableString);
                }
            });
        }

        function exportExcel2(){
            let data = {
                from: from2,
                to: to2,
                company: company2
            };

            window.location.href = "{{ route('report.sales') }}?" + $.param(data);
        }
    </script>
@endpush