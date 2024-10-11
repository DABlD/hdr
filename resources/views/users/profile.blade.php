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
                            <img src="{{ asset("images/default_avatar.png") }}" width="150" height="150" id="preview">

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
                                <tbody>
                                    @foreach($nurses as $nurse)
                                        <tr>
                                            <td class="nurse" data-id="{{ $nurse->user->id }}" onclick="selectRow(this)">
                                                {{ $nurse->user->lname }}, {{ $nurse->user->fname }} {{ $nurse->user->mname }} ({{ substr($nurse->user->gender, 0, 1) }}{{ now()->parse($data->user->birthday)->age }})
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($nurses as $nurse)
                                        <tr>
                                            <td class="nurse" data-id="{{ $nurse->user->id }}" onclick="selectRow(this)">
                                                {{ $nurse->user->lname }}, {{ $nurse->user->fname }} {{ $nurse->user->mname }} ({{ substr($nurse->user->gender, 0, 1) }}{{ now()->parse($data->user->birthday)->age }})
                                            </td>
                                        </tr>
                                    @endforeach
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

                        <a class="btn btn-primary" data-toggle="tooltip" title="View" onclick="viewAssistant()">VIEW</a>
                        <a class="btn btn-danger" data-toggle="tooltip" title="Delete" onclick="deleteAssistant()">DELETE</a>

                        <div class="float-right">
                            <a class="btn btn-success" data-toggle="tooltip" title="Add" onclick="addAssistant()">Add</a>
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
                                        Contact Information
                                    </a>
                                </li>
                                &nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab3" data-toggle="tab">
                                        Employment Information
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
                            </div>

                            <div class="chart tab-pane" id="tab2" style="position: relative;">
                                <br>
                            </div>
                            <div class="chart tab-pane" id="tab3" style="position: relative;">
                                <br>
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
	<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">

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
    </style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>

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
		});

        function selectRow(row){
            $('.nurse').parent().removeClass('selected');
            $(row).parent().addClass('selected');
        }

        function viewAssistant(){
            console.log("view");
        }

        function deleteAssistant(){
            console.log("delete");
        }

        function addAssistant(){
            console.log("add");
        }
	</script>
@endpush