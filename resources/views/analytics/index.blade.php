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
                                <input type="text" id="from" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="to">To</label>
                                <input type="text" id="to" class="form-control">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body custom-tabs">
                                        <ul class="nav nav-pills ml-auto" style="padding-left: revert;" style="text-align: center;">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#data_analysis" data-toggle="tab" data-href="data_analysis">Data Analysis</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                                <a class="nav-link" href="#results" data-toggle="tab" data-href="results">Results</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                                <a class="nav-link" href="#classification" data-toggle="tab" data-href="classification">Classification</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                        </ul>

                                        <br>

                                        <div class="tab-content p-0">
                                            <div class="chart tab-pane active" id="data_analysis" style="position: relative;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span style="font-weight: bold;">
                                                            Total Clients/Examinees Seen:
                                                        </span>
                                                        <span class="count">0</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="classification" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <canvas id="gender" width="100%"></canvas>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="age" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <canvas id="bmi" width="100%"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart tab-pane" id="results" style="position: relative;">2</div>
                                            <div class="chart tab-pane" id="classification" style="position: relative;">3</div>
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

        .custom-tabs .nav {
            display: flex;
            justify-content: flex-start;   /* or center */
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: .75rem;
        }

        .custom-tabs .nav-link {
            border-radius: 999px;
            padding: .45rem .9rem;
            background: #f1f5f9;           /* soft slate */
            color: #475569;                 /* slate-700 */
            font-weight: 600;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .custom-tabs .nav-link:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .custom-tabs .nav-link.active {
            background: var(--tab-accent);
            color: #fff;
            box-shadow: 0 6px 16px rgba(74,192,192,.25);
        }

        .custom-tabs .tab-pane {
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0,0,0,.03);
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
        var from = moment().subtract(3,'months').format("YYYY-MM-DD");
        var to = moment().format("YYYY-MM-DD");
        var name = "";

        var ctx1, chart1;

        $(document).ready(() => {
            $('#from').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: from
            });

            $('#to').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: to
            });

            getChart1();

            $('#from, #to, #name').on('change', e => {
                window[e.target.id] = e.target.value;
                getChart1();
            });
        });

        function getFilters(){
            return {
                from: from,
                to: to,
                name: name
            }
        }

        function getChart1(){


            $.ajax({
                url: "{{ route("analytics.getReport1") }}",
                data: {filters: getFilters()},
                success: result => {
                    result = JSON.parse(result);
                    $('#data_analysis .count').html(result.length);

                    let classifications = [];
                    let genders = [];
                    let ages = [];
                    let bmis = [];

                    result.forEach(patient => {
                        classifications.push(patient.classifications ?? "Pending");
                        genders.push(patient.genders);
                        ages.push(moment().diff(moment(patient.birthday), 'years'));

                        let qwa = JSON.parse(patient.question_with_answers);
                        qwa.forEach(answer => {
                            if(answer.id == 173){
                                if(answer.answer){
                                    bmis.push(answer.answer);
                                }
                            }
                        });
                    });

                    classifications = classifications.reduce((classification, item) => {
                      classification[item] = (classification[item] || 0) + 1;
                      return classification;
                    }, {});

                    console.log(Object.keys(classifications));
                    console.log(Object.values(classifications));

                    ctx1 = document.getElementById('classification').getContext('2d');
                    chart1 = new Chart(ctx1, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(classifications),
                            datasets: Object.values(classifications)
                        }
                    });

                    console.log(gender);
                    console.log(age);
                    console.log(bmi);
                }
            });
        }
    </script>
@endpush