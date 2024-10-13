@extends('layouts.app')
@section('content')

@php
    $col = function($label, $name, $value, $type = "text", $mb = 3, $options = null){
        if($type == "select"){
            $optionString = "";
            foreach($options as $option){
                $optionString .= "<option value='$option'>$option</option>";
            }

            echo "
                <div class='col'>
                    <div class='mb-$mb'>
                        <label class='form-label' for='$name'>
                            <strong>
                                $label
                            </strong>
                        </label>

                        <select class='form-control' id='$name'>
                            <option value='' selected=''>-</option>
                            $optionString
                        </select>
                    </div>
                </div>
            ";
        }
        else{
            echo "
                <div class='col'>
                    <div class='mb-$mb'>
                        <label class='form-label' for='$name'>
                            <strong>
                                $label
                            </strong>
                        </label>
                        <input class='form-control' type='$type' id='$name' value='$value'>
                    </div>
                </div>
            ";
        }
    }
@endphp

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <section class="col-md-4 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Photo
                        </h3>

                        {{-- @include('users.includes.toolbar') --}}
                    </div>

                    <div class="card-body table-responsive">
                    	<div style="text-align: center;">
                            <img src="{{ asset(auth()->user()->avatar) }}" width="150" height="150" id="preview">

                            <br>
                            <label for="files" class="btn">Upload New Image</label>
                            <input id="files" class="d-none" type="file" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Assistants
                        </h3>

                        {{-- @include('users.includes.toolbar') --}}
                    </div>

                    <div class="card-body table-responsive">
                        <div>
                            <table class="table table-hover">
                                <tbody id="assistantList">
                                    @foreach($nurses as $nurse)
                                        <tr>
                                            <td class="nurse" data-id="{{ $nurse->user->id }}" onclick="selectRow(this)">
                                                {{ $nurse->user->lname }}, {{ $nurse->user->fname }} {{ $nurse->user->mname }} ({{ substr($nurse->user->gender, 0, 1) }}{{ now()->parse($data->user->birthday)->age }})
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <a class="btn btn-primary" data-toggle="tooltip" title="View" onclick="viewAssistant()">
                            View
                        </a>
                        <a class="btn btn-danger" data-toggle="tooltip" title="Delete" onclick="deleteAssistant()">
                            Delete
                        </a>

                        <div class="float-right">
                            <a class="btn btn-success" data-toggle="tooltip" title="Add" onclick="addAssistant()">Add</a>
                        </div>
                    </div>
                </div>

                <div class="card hidden group2">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Signature
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <div style="text-align: center;">
                            <img src="{{ asset($data->signature) }}" alt="No Signature" width="300" height="200" id="preview2">

                            <br>
                            <label for="files2" class="btn">Upload New Image</label>
                            <input id="files2" class="d-none" type="file" accept="image/*">
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-md-8 connectedSortable informations">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <ul class="nav nav-pills ml-auto" style="padding-left: revert;">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab1" data-toggle="tab">
                                        Personal Information
                                    </a>
                                </li>
                                &nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab2" data-toggle="tab">
                                        Professional Information
                                    </a>
                                </li>
                                &nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab3" data-toggle="tab">
                                        Account Information
                                    </a>
                                </li>
                            </ul>
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <div class="tab-content p-0">
                            <div class="chart tab-pane active" id="tab1" style="position: relative;">

                                <div class="row">
                                    {{ $col("First Name", "fname", $data->user->fname) }}
                                    {{ $col("Middle Name", "mname", $data->user->mname) }}
                                    {{ $col("Last Name", "lname", $data->user->lname) }}
                                    {{ $col("Suffix", "suffix", $data->user->suffix, "select", null, ["Jr", "Sr", "II", "III", "IV", "V"]) }}
                                </div>

                                <div class="row">
                                    {{ $col("Gender", "gender", $data->user->gender, "select", null, ["Male", "Female"]) }}
                                    {{ $col("Birth Date", "birthday", $data->user->birthday) }}
                                    {{ $col("Email", "email", $data->user->email) }}
                                    {{ $col("Contact", "contact", $data->user->contact) }}
                                </div>

                                <div class="row">
                                    {{ $col("Address", "address", $data->user->address, "text", 12) }}
                                </div>

                                <div class="row">
                                    {{ $col("TIN", "tin", $data->tin) }}
                                    {{ $col("Philhealth", "philhealth", $data->philhealth) }}
                                    {{ $col("SSS", "sss", $data->sss) }}
                                    {{ $col("Pagibig", "pagibig", $data->pagibig) }}
                                </div>

                                <div class="float-right">
                                    <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save1()">
                                        Save
                                    </a>
                                </div>
                            </div>

                            <div class="chart tab-pane" id="tab2" style="position: relative;">
                                <div class="row">
                                    {{ $col("License Number", "license_number", $data->license_number) }}
                                    {{ $col("S2 Number", "s2_number", $data->s2_number) }}
                                    {{ $col("PTR Number", "ptr", $data->ptr) }}
                                </div>

                                <div class="row">
                                    {{ $col("Specialization", "specialization", $data->specialization) }}
                                    {{ $col("Pharma Partner", "pharma_partner", $data->pharma_partner) }}
                                    {{ $col("Title", "Title", $data->title) }}
                                </div>

                                <div class="float-right">
                                    <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save2()">
                                        Save
                                    </a>
                                </div>
                            </div>

                            <div class="chart tab-pane" id="tab3" style="position: relative;">
                                <div class="row">
                                    {{ $col("Username", "username", $data->user->username) }}
                                </div>

                                <div class="float-right">
                                    <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save3()">
                                        Save
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card hidden group2">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-handshake-simple mr-1"></i>
                            Medical Association
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <div>
                            <table class="table table-hover">
                                <tbody id="medicalAssociation">
                                </tbody>
                            </table>
                        </div>


                        <div class="float-right">
                            <a class="btn btn-success" data-toggle="tooltip" title="Add" onclick="addAssociation()">
                                Add
                            </a>
                            <a class="btn btn-danger" data-toggle="tooltip" title="Delete" onclick="deleteAssociation()">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card hidden group2">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Diplomate
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <div>
                            {{ $col("Diplomate 1", "dp1", null, "text", 12) }}
                            {{ $col("Diplomate 2", "dp2", null, "text", 12) }}
                            {{ $col("Diplomate 3", "dp3", null, "text", 12) }}
                        </div>


                        <div class="float-right">
                            <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save2()">
                                Save
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card hidden group3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lock mr-1"></i>
                            Change Password
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <div>
                            {{ $col("New Password", "new_password", null, "password", 12) }}
                            {{ $col("Confirm Password", "confirm_password", null, "password", 12) }}
                        </div>


                        <div class="float-right">
                            <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save4()">
                                Save
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</section>

@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        .informations .nav-pills>li>a {
            border-top: 3px solid !important;
        }

        .informations .nav-link.active {
            color: #fff !important;
            background-color: #b96666 !important;
        }

        .card-header{
            background-color: rgb(131, 200, 229);
        }

        .mb-12{
            margin-bottom: 1rem !important;
        }

        .selected{
            background-color: #e8ecee;
        }

        #swal2-html-container .label{
            font-weight: bold;
        }

        #swal2-html-container .pInfo{
            color: deepskyblue;
        }
    </style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

	<script>
		$(document).ready(()=> {
            $('.m-0').html('Account Settings');

            // PREVIEW IMAGES
            $('#files').on('change', e => {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(e.target.files[0]);
                updatePhoto();
            });

            // PREVIEW SIGNATURE
            $('#files2').on('change', e => {
                var reader2 = new FileReader();
                reader2.onload = function (e) {
                    $('#preview2').attr('src', e.target.result);
                }

                reader2.readAsDataURL(e.target.files[0]);
                updateSignature();
            });

            // FILL FIELDS
            $('#suffix').val("{{ $data->user->suffix }}")
            $('#gender').val("{{ $data->user->gender }}")
            $('#birthday').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                maxDate: moment().format(dateFormat)
            });

            $('[href="#tab1"]').on('click', () => {
                $('.hidden').hide();
            });

            $('[href="#tab2"]').on('click', () => {
                $('.hidden').hide();
                $('.group2').show();
                getMedicalAssociation();
                getDiplomate();
            });

            $('[href="#tab3"]').on('click', () => {
                $('.hidden').hide();
                $('.group3').show();
            });

            $('[href="#tab1"]').click();
            // $('[href="#tab3"]').click(); //  FOR DEBUG
		});

        async function updatePhoto(){
            let formData = new FormData();

            formData.append('id', {{ auth()->user()->id }});
            formData.append('avatar', $("#files").prop('files')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            await fetch('{{ route('user.update') }}', {
                method: "POST", 
                body: formData
            });

            ss('Success');
            reload();
        }

        async function updateSignature(){
            let formData = new FormData();

            formData.append('id', {{ $data->id }});
            formData.append('signature', $("#files2").prop('files')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            await fetch('{{ route('doctor.update') }}', {
                method: "POST", 
                body: formData
            });

            ss('Success');
            reload();
        }

        function selectRow(row){
            $('.nurse').parent().removeClass('selected');
            $(row).parent().addClass('selected');
        }

        function viewAssistant(){
            let id = $('tr.selected td').data('id');
            if(id){
                $.ajax({
                    url: '{{ route('user.get') }}',
                    data: {
                        select: "*",
                        where: ["id", id],
                        load: ["nurse"]
                    },
                    success: result => {
                        result = JSON.parse(result)[0];
                        showAssistant(result);
                    }
                })
            }
            else{
                se("No Selected Assistant");
            }
        }

        function showAssistant(user){
            Swal.fire({
                title: "Nurse Information",
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
                                        <img src="${user.avatar}" alt="avatar" width="120" height="120">
                                    </div>

                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">SSS</span>
                                        <br>
                                        <span class="pInfo">${user.nurse.sss}</span>
                                    </div>

                                    <br>

                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">TIN</span>
                                        <br>
                                        <span class="pInfo">${user.nurse.tin}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Philhealth</span>
                                        <br>
                                        <span class="pInfo">${user.nurse.philhealth}</span>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Pagibig</span>
                                        <br>
                                        <span class="pInfo">${user.nurse.pagibig}</span>
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
                                            <span class="label">Gender</span>
                                            <br>
                                            <span class="pInfo">${user.gender}</span>
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

                                            Account Information

                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">
                                    
                                    <div class="col-md-12 pInfo-left">
                                        <span class="label">Username</span>
                                        <br>
                                        <span class="pInfo">${user.username ?? "-"}</span>
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

        function deleteAssistant(){
            let id = $('#assistantList tr.selected td').data('id');
            if(id){
                swal.showLoading();
                update({
                    url: "{{ route('nurse.update') }}",
                    data: {
                        id: id,
                        doctor_id: null
                    },
                    message: "Success"
                },  () => {
                    $('#assistantList tr.selected').remove();
                });
            }
            else{
                se("No Selected Assistant");
            }
        }

        function addAssistant(){
            $.ajax({
                url: "{{ route('nurse.get') }}",
                data: {
                    select: "*",
                    where: ["doctor_id", null],
                    load: ['user']
                },
                success: nurses => {
                    nurses = JSON.parse(nurses);
                    nurseString = "";


                    nurses.forEach(nurse => {
                        let age = nurse.user.birthday ? moment().diff(moment(nurse.user.birthday), "years") : "";

                        nurseString += `
                            <option value="${nurse.user.id}">
                                ${nurse.user.lname}, ${nurse.user.fname} (${nurse.user.gender[0] + age})
                            </option>
                        `;
                    });

                    Swal.fire({
                        title: "Assistants",
                        html: `
                            <select class="form-control" id="assistant">
                                <option value="">Select Assistant</option>
                                ${nurseString}
                            </select>
                        `,
                        showCancelButton: true,
                        cancelButtonColor: errorColor,
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            swal.showLoading();
                            return new Promise(resolve => {
                                setTimeout(() => {
                                    if($('#assistant').val() == ""){
                                        Swal.showValidationMessage('Select One');
                                    }
                                resolve()}, 500);
                            });
                        },
                    }).then(result => {
                        if(result.value){
                            let id = $('#assistant').val();

                            update({
                                url: "{{ route('nurse.update') }}",
                                data: {
                                    id: id,
                                    doctor_id: {{ auth()->user()->id }}
                                }
                            }, () => {
                                ss("Success");
                                displayNewAssistant(id);
                            })
                        }
                    });
                }
            })
        }

        function displayNewAssistant(id){
            $.ajax({
                url: "{{ route('nurse.get') }}",
                data: {
                    select: "*",
                    where: ["user_id", id],
                    load: ['user']
                },
                success: nurse => {
                    nurse = JSON.parse(nurse)[0];

                    let age = nurse.user.birthday ? moment().diff(moment(nurse.user.birthday), "years") : "";

                    $('#assistantList').append(`
                        <tr>
                            <td class="nurse" data-id="${id}" onclick="selectRow(this)">
                                ${nurse.user.lname}, ${nurse.user.fname} (${nurse.user.gender[0] + age})
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function save1(){
            swal.showLoading();
            update({
                url: "{{ route('user.update') }}",
                data: {
                    id: {{ $data->user->id }},
                    fname: $('#fname').val(),
                    mname: $('#mname').val(),
                    lname: $('#lname').val(),
                    suffix: $('#suffix').val(),
                    gender: $('#gender').val(),
                    birthday: $('#birthday').val(),
                    email: $('#email').val(),
                    contact: $('#contact').val(),
                    address: $('#address').val()
                }
            }, () => {
                update({
                    url: "{{ route('doctor.update') }}",
                    data: {
                        id: {{ $data->id }},
                        tin: $('#tin').val(),
                        sss: $('#sss').val(),
                        philhealth: $('#philhealth').val(),
                        pagibig: $('#pagibig').val(),
                    }
                }, () => {
                    ss("Success");
                });
            });
        }

        function save2(){
            swal.showLoading();
            update({
                url: "{{ route('doctor.update') }}",
                data: {
                    id: {{ $data->id }},
                    diplomate: [
                        $('#dp1').val(),
                        $('#dp2').val(),
                        $('#dp3').val()
                    ]
                }
            }, () => {
                ss("Success");
            });
        }

        function save3(){
            swal.showLoading();
            update({
                url: "{{ route('user.update') }}",
                data: {
                    id: {{ $data->user->id }},
                    username: $('#username').val()
                }
            }, () => {
                ss("Success");
            });
        }

        function save4(){
            swal.showLoading();
            let pass = $('#new_password').val();
            let cpass = $('#confirm_password').val();

            if(pass == ""){
                se("Password must be at least 6 characters long.");
            }
            else if(pass === cpass){
                update({
                    url: "{{ route('user.updatePassword') }}",
                    data: {
                        id: {{ $data->user->id }},
                        password: pass
                    }
                }, () => {
                    ss("Success");
                    $('#new_password').val("");
                    $('#confirm_password').val("");
                });
            }
            else{
                se("Password not match. Try Again");
            }
        }

        function getMedicalAssociation(){
            $.ajax({
                url: "{{ route("doctor.get") }}",
                data: {
                    select: "medical_association",
                    where: ["user_id", {{ auth()->user()->id }}]
                },
                success: association => {
                    association = JSON.parse(association)[0];

                    if(association.medical_association){
                        let array = JSON.parse(association.medical_association);
                        $('#medicalAssociation').html("");
                        array.forEach(assoc => {
                            $('#medicalAssociation').append(`
                                <tr onClick="selectRow2(this)">
                                    <td class="data-ma">${assoc}</td>
                                </tr>
                            `);
                        });
                    }
                }
            })
        }

        function getDiplomate(){
            let diplomates = '{!! $data->diplomate !!}';

            if(diplomates){
                diplomates = JSON.parse(diplomates);

                diplomates.forEach((diplomate, key) => {
                    $('#dp' + (++key)).val(diplomate);
                })
            }
        }

        function addAssociation(){
            Swal.fire({
                title: "Select Association to add",
                html: `
                    <select id="selectAssociation">
                        <option value="">N/A</option>
                        <option>Philippine Dental Association</option>
                        <option>Philippine Medical Association</option>
                        <option>Blue Cross Shield Association</option>
                        <option>Philippine Veterinary Medical Association</option>
                        <option>SBCA assoc.</option>
                        <option>Philippine Heart Association</option>
                        <option>Las Piñas Medical Society</option>
                        <option>PRx</option>
                    </select>
                `,
                didOpen: () => {
                    $('#selectAssociation').select2({
                        tag: true,
                    });

                    $('#select2-selectAssociation-container').css('text-align', 'left');
                },
                preConfirm: () => {
                    swal.showLoading();
                    return new Promise(resolve => {
                        setTimeout(() => {
                            if($('#selectAssociation').val() == ""){
                                Swal.showValidationMessage('Select One');
                            }
                        resolve()}, 500);
                    });
                },
            }).then(result => {
                if(result.value){
                    $('#medicalAssociation').append(`
                        <tr onclick="selectRow2(this)">
                            <td class="data-ma">${$('#selectAssociation').val()}</td>
                        </tr>
                    `);

                    let assocs = $('.data-ma');
                    let array = [];

                    assocs.each((key, assoc) => {
                        array.push(assoc.innerHTML);
                    });

                    update({
                        url: "{{ route('doctor.update') }}",
                        data: {
                            id: {{ $data->id }},
                            medical_association: JSON.stringify(array)
                        }
                    }, () => {
                        ss("Successfully Added");
                    });
                }
            });
        }

        function selectRow2(row){
            $('.data-ma').parent().removeClass('selected');
            $(row).addClass('selected');
        }

        function deleteAssociation(){
            if($('#medicalAssociation tr.selected').length){
                $('#medicalAssociation tr.selected').remove();

                let assocs = $('.data-ma');
                let array = [];

                assocs.each((key, assoc) => {
                    array.push(assoc.innerHTML);
                });

                update({
                    url: "{{ route('doctor.update') }}",
                    data: {
                        id: {{ $data->id }},
                        medical_association: JSON.stringify(array)
                    }
                }, () => {
                    ss("Successfully Added");
                });
            }
            else{
                se("Select one to delete");
            }
        }
	</script>
@endpush

