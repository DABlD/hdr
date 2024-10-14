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
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Reports
                        </h3>

                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab1" data-toggle="tab">Tab 1</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab2" data-toggle="tab">Tab 2</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="chart tab-pane active" id="tab1" style="position: relative; height: 300px;">
                                TAB 1
                            </div>
                            <div class="chart tab-pane" id="tab2" style="position: relative; height: 300px;">
                                TAB 2
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection