@extends('layouts.app')
@section('content')

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
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">
                                                <strong>
                                                    First Name
                                                </strong>
                                            </label>
                                            <input class="form-control" type="text"placeholder="Name" name="firstname" value="{{ $data->user->fname }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">
                                                <strong>
                                                    Middle Name
                                                </strong>
                                            </label>
                                            <input class="form-control" type="text"placeholder="Name" name="middlename" value="{{ $data->user->mname }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">
                                                <strong>
                                                    Last Name
                                                </strong>
                                            </label>
                                            <input class="form-control" type="text"placeholder="Name" name="lastname" value="{{ $data->user->lname }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">
                                                <strong>
                                                    Suffix
                                                </strong>
                                            </label>

                                            <select id="_suffix" class="form-control" name="suffix">
                                                <option value="" selected="">None</option>
                                                <option value="Jr">Jr</option>
                                                <option value="Sr">Sr</option>
                                                <option value="II">II</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                                <option value="V">V</option>
                                            </select>
                                        </div>
                                    </div>
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
    </style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>

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
		});
	</script>
@endpush