@extends('layouts.app-company')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Employee List
                        </h3>

                        <div class="card-tools">
                            <a class="btn btn-info" data-toggle="tooltip" title="Import From Excel" onclick="importEmployees()">
                                <i class="fas fa-file-excel"></i>
                                Import
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="table" class="table table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Surname</th>
                                    <th>First Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Contact</th>
                                    <th>Nationality</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        .label{
            font-weight: bold;
        }

        .pInfo{
            color: deepskyblue;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>

    <script>
        $(document).ready(()=> {
            var table = $('#table').DataTable({
                ajax: {
                    url: "{{ route('datatable.patient') }}",
                    dataType: "json",
                    dataSrc: "",
                    data: {
                        select: "*",
                        where: ["company_name", "=", "{{ $title }}"],
                        load: ['user']
                    }
                },
                columns: [
                    {data: 'id'},
                    {data: 'user.lname'},
                    {data: 'user.fname'},
                    {data: 'user.gender'},
                    {data: 'user.birthday'},
                    {data: 'user.contact'},
                    {data: 'user.nationality'},
                    {data: 'actions'},
                ],
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 4,
                        render: birthday => {
                            return birthday ? toDate(birthday) + " " + `(${moment().diff(birthday, 'years')})` : "-";
                        },
                    }
                ],
            });
        });

        function view(id){
            $.ajax({
                url: "{{ route('user.get') }}",
                data: {
                    select: '*',
                    where: ['id', id],
                    load: ['patient']
                },
                success: patient => {
                    patient = JSON.parse(patient)[0];
                    showDetails(patient);
                }
            })
        }

        function showDetails(user){
            Swal.fire({
                title: "Patient Information",
                html: `
                    <br>
                    <div class="row">

                        <section class="col-lg-3">
                            <div class="card">
                                <div class="card-header row">
                                    <div class="col-md-12">
                                        <h3 class="card-title" style="width: 100%; text-align: left;">
                                            <i class="fas fa-user mr-1"></i>

                                            Basic Information

                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="col-md-12">
                                        <img src="../${user.avatar}" alt="avatar" width="120" height="120">
                                    </div>

                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Patient ID</span>
                                        <br>
                                        <span class="pInfo">${user.patient.patient_id}</span>
                                    </div>

                                    <br>

                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">HMO Provider</span>
                                        <br>
                                        <span class="pInfo">${user.patient.hmo_provider}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">HMO Number</span>
                                        <br>
                                        <span class="pInfo">${user.patient.hmo_number}</span>
                                    </div>

                                </div>
                            </div>
                        </section>

                        <section class="col-lg-9">
                            <div class="card">
                                <div class="card-header row">
                                    <div class="col-md-12">
                                        <h3 class="card-title" style="width: 100%; text-align: left;">
                                            <i class="fas fa-user mr-1"></i>

                                            General Information

                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">First Name</span>
                                            <br>
                                            <span class="pInfo">${user.fname ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Middle Name</span>
                                            <br>
                                            <span class="pInfo">${user.mname ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Last Name</span>
                                            <br>
                                            <span class="pInfo">${user.lname ?? "-"}</span>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">Birthday</span>
                                            <br>
                                            <span class="pInfo">${toDate(user.birthday)}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Age</span>
                                            <br>
                                            <span class="pInfo">${moment().diff(user.birthday, 'years')}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Birth Place</span>
                                            <br>
                                            <span class="pInfo">${user.birth_place}</span>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">Gender</span>
                                            <br>
                                            <span class="pInfo">${user.gender}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Civil Status</span>
                                            <br>
                                            <span class="pInfo">${user.civil_status}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Nationality</span>
                                            <br>
                                            <span class="pInfo">${user.nationality}</span>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">Religion</span>
                                            <br>
                                            <span class="pInfo">${user.religion}</span>
                                        </div>
                                    </div>

                                    <br>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="label">Address</span>
                                            <br>
                                            <span class="pInfo">${user.address}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col-lg-3">
                            <div class="card">
                                <div class="card-header row">
                                    <div class="col-md-12">
                                        <h3 class="card-title" style="width: 100%; text-align: left;">
                                            <i class="fas fa-phone mr-1"></i>

                                            Contact Information

                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Email</span>
                                        <br>
                                        <span class="pInfo">${user.email ?? "-"}</span>
                                    </div>

                                    <br>

                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Contact</span>
                                        <br>
                                        <span class="pInfo">${user.contact ?? "-"}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Mother's Name</span>
                                        <br>
                                        <span class="pInfo">${user.patient.mothers_name ?? "-"}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Father's Name</span>
                                        <br>
                                        <span class="pInfo">${user.patient.fathers_name ?? "-"}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Guardian's Name</span>
                                        <br>
                                        <span class="pInfo">${user.patient.guardians_name ?? "-"}</span>
                                    </div>

                                </div>
                            </div>
                        </section>

                        <section class="col-lg-9">
                            <div class="card">
                                <div class="card-header row">
                                    <div class="col-md-12">
                                        <h3 class="card-title" style="width: 100%; text-align: left;">
                                            <i class="fas fa-briefcase mr-1"></i>

                                            Employment Information

                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">Employment Status</span>
                                            <br>
                                            <span class="pInfo">${user.patient.employment_status ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Company Name</span>
                                            <br>
                                            <span class="pInfo">${user.patient.company_name ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">Position</span>
                                            <br>
                                            <span class="pInfo">${user.patient.company_position ?? "-"}</span>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="label">Contact</span>
                                            <br>
                                            <span class="pInfo">${user.patient.company_contact ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">SSS</span>
                                            <br>
                                            <span class="pInfo">${user.patient.sss ?? "-"}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="label">TIN</span>
                                            <br>
                                            <span class="pInfo">${user.patient.tin_number ?? "-"}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                `,
                width: '1200px',
                confirmButtonText: 'OK',
                // showCancelButton: true,
                // cancelButtonColor: errorColor,
                // cancelButtonText: 'Cancel',
                didOpen: () => {
                    $('.pInfo').parent().css('text-align', 'left');
                    $('#swal2-html-container .card-header').css('margin', "1px");
                    $('#swal2-html-container .card-header').css('background-color', "#83c8e5");
                    $('#swal2-html-container .card-body').css('border', "1px solid rgba(0,0,0,0.125)");
                }
            });
        }

        function importEmployees(){
            Swal.fire({
                title: 'Select Excel File',
                html: `
                    <input id="files" class="form-control" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                `,
                preConfirm: () => {
                    if($("#files").prop('files')[0]){
                        let formData = new FormData();

                        formData.append('company', "{{ $title }}");
                        formData.append('excel', $("#files").prop('files')[0]);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                        fetch('{{ route('company.import') }}', {
                            method: "POST", 
                            body: formData
                        });

                        ss('Success');
                        reload();
                    }
                    else{
                        Swal.showValidationMessage("No Selected File");
                    }
                }
            })
        }
    </script>
@endpush