@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            List
                        </h3>

                        @include('patients.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Company</th>
                    				<th>Package</th>
                    				<th>PAX</th>
                    				<th>Completed</th>
                    				<th>Pending</th>
                    				<th>Status</th>
                    				<th>Created At</th>
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

	    #swal2-html-container .nav-pills>li>a {
	    	border-top: 3px solid !important;
	    }

	    #swal2-html-container .nav-link.active {
	    	color: #fff !important;
	    	background-color: #337ab7 !important;
	    }

	    .answer{
	    	text-align: center;
	    }
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>

	<script>
		var fCompany = "%%";

		{{-- <th>Company</th>
		<th>Package</th>
		<th>PAX</th>
		<th>Completed</th>
		<th>Pending</th>
		<th>Status</th>
		<th>Created At</th> --}}

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.transactions') }}",
                	dataType: "json",
                	dataSrc: "",
	                data: f => {
	                    f.select = ["*"];
	                    f.filters = getFilters();
	                    f.load = ["package"];
	                }
				},
				columns: [
					{data: 'company'},
					{data: 'package.name'},
					{data: 'pax'},
					{data: 'completed'},
					{data: 'pending'},
					{data: 'status'},
					{data: 'created_at'}
				],
        		pageLength: 10,
	            columnDefs: [
	                {
	                    targets: 6,
	                    render: created_at => {
	                        return toDateTime(created_at)
	                    },
	                }
	            ],
			});

			$('#fCompany').on('change', e => {
	            e = $(e.target);
	            fCompany = e.val();
	            reload();
	        });
		});
		
		function getFilters(){
			return {
			    fCompany: fCompany,
			}
		}

		function create(){
			Swal.fire({
				title: "Enter Patient Details",
				html: `
					<ul class="nav nav-pills ml-auto" style="padding-left: revert;">
					    <li class="nav-item">
					        <a class="nav-link active" href="#tab1" data-toggle="tab">Personal Information</a>
					    </li>
					    &nbsp;
					    <li class="nav-item">
					        <a class="nav-link" href="#tab2" data-toggle="tab">Contact Information</a>
					    </li>
					    &nbsp;
					    <li class="nav-item">
					        <a class="nav-link" href="#tab3" data-toggle="tab">Employment Information</a>
					    </li>
					</ul>
					<div class="tab-content p-0">
					    <div class="chart tab-pane active" id="tab1" style="position: relative;">
					    	<br>
					    	<img src="{{ asset("images/default_avatar.png") }}" alt="PHOTO" width="100" height="100" id="preview">
					    	<br>
					    	<label for="files" class="btn">Upload Image</label>
					    	<br>
					    	<input id="files" style="visibility:hidden;" type="file" accept="image/*">
	        				<div class="row iRow">
	        				    <div class="col-md-3 iLabel">
	        				        Prefix
	        				    </div>
	        				    <div class="col-md-9 iInput">
	        				        <select name="prefix" class="form-control">
	        				        	<option value="">Select Prefix*</option>
	        				        	<option value="Mr.">Mr.</option>
	        				        	<option value="Ms.">Ms.</option>
	        				        	<option value="Mrs.">Mrs.</option>
	        				        	<option value="Dr.">Dr.</option>
	        				        	<option value="Engr.">Engr.</option>
	        				        	<option value="Prof.">Prof.</option>
	        				        	<option value="Atty.">Atty.</option>
	        				        	<option value="Hon.">Hon.</option>
	        				        </select>
	        				    </div>
	        				</div>
					        ${input("fname", "First Name", null, 3, 9)}
					        ${input("mname", "Middle Name", null, 3, 9)}
					        ${input("lname", "Last Name", null, 3, 9)}
	        				<div class="row iRow">
	        				    <div class="col-md-3 iLabel">
	        				        Suffix
	        				    </div>
	        				    <div class="col-md-9 iInput">
	        				        <select name="suffix" class="form-control">
	        				        	<option value="">Select Suffix*</option>
	        				        	<option value="Jr.">Jr.</option>
	        				        	<option value="Sr.">Sr.</option>
	        				        	<option value="I">I</option>
	        				        	<option value="II">II</option>
	        				        	<option value="III">III</option>
	        				        	<option value="IV">IV</option>
	        				        	<option value="V">V</option>
	        				        </select>
	        				    </div>
	        				</div>
					        ${input("birthday", "Birthday", null, 3, 9)}
					        ${input("birth_place", "Birth Place", null, 3, 9)}
	        				<div class="row iRow">
	        				    <div class="col-md-3 iLabel">
	        				        Gender
	        				    </div>
	        				    <div class="col-md-9 iInput">
	        				        <select name="gender" class="form-control">
	        				        	<option value="">Select Gender</option>
	        				        	<option value="Male">Male</option>
	        				        	<option value="Female">Female</option>
	        				        </select>
	        				    </div>
	        				</div>
	        				<div class="row iRow">
	        				    <div class="col-md-3 iLabel">
	        				        Civil Status
	        				    </div>
	        				    <div class="col-md-9 iInput">
	        				        <select name="civil_status" class="form-control">
	        				        	<option value="">Select Civil Status</option>
	        				        	<option value="Single">Single</option>
	        				        	<option value="Married">Married</option>
	        				        	<option value="Widowed">Widowed</option>
	        				        </select>
	        				    </div>
	        				</div>
	                        ${input("nationality", "Nationality", "Filipino", 3, 9)}
	                        ${input("religion", "Religion", null, 3, 9)}
	                		${input("address", "Address", null, 3, 9)}
					    </div>
					    <div class="chart tab-pane" id="tab2" style="position: relative;">
					    	<br>
	                        ${input("contact", "Contact", null, 3, 9)}
	        				${input("email", "Email", null, 3, 9, 'email')}
					    </div>
					    <div class="chart tab-pane" id="tab3" style="position: relative;">
					    	<br>
			                ${input("employment_status", "Employment Status", null, 3, 9)}
			                <div class="row iRow">
	        				    <div class="col-md-3 iLabel">
	        				        Company Name
	        				    </div>
	        				    <div class="col-md-9 iInput">
	        				        <select name="company_name" class="form-control">
	        				        	<option value="">Select Company</option>
	        				        	@foreach($companies as $company)
	        				        	    @if($company != null)
	        				        	        <option value="{!! $company !!}">{{ $company }}</option>
	        				        	    @endif
	        				        	@endforeach
	        				        </select>
	        				    </div>
	        				</div>
			                ${input("company_position", "Position", null, 3, 9)}
			                ${input("sss", "SSS", null, 3, 9)}
			                ${input("tin_number", "TIN", null, 3, 9)}
			                ${input("hmo_provider", "HMO Provider", null, 3, 9)}
			                ${input("hmo_number", "HMO Number", null, 3, 9)}
					    </div>
					</div>
				`,
				allowOutsideClick: false,
				allowEscapeKey: false,
				width: '800px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				position: 'top',
				didOpen: () => {
					$('[name="birthday"]').flatpickr({
						altInput: true,
						altFormat: "M j, Y",
						dateFormat: "Y-m-d",
						maxDate: moment().format("YYYY-MM-DD")
					});
					$('#files').on('change', e => {
					    var reader = new FileReader();
					    reader.onload = function (e) {
					        $('#preview').attr('src', e.target.result);
					    }
					    reader.readAsDataURL(e.target.files[0]);
					});
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;
				    	let fname = $('[name="fname"]').val();
				    	let lname = $('[name="lname"]').val();
				    	let birthday = $('[name="birthday"]').val();
				    	let gender = $('[name="gender"]').val();
				    	let civil_status = $('[name="civil_status"]').val();
				    	let contact = $('[name="contact"]').val();
				    	let email = $('[name="email"]').val();

				    	let check = ["fname","lname","gender","civil_status","contact","email"];
				    	let flag = false;

				    	check.forEach(field => {
				    		$(`[name="${field}"]`).removeClass('border-danger');
				    		if($(`[name="${field}"]`).val() == ""){
				    			$(`[name="${field}"]`).addClass('border-danger');
				    			flag = true;
				    		}
				    	});

				    	field = "birthday";
			    		$(`[name="${field}"]`).next().removeClass('border-danger');
			    		if($(`[name="${field}"]`).val() == ""){
			    			$(`[name="${field}"]`).next().addClass('border-danger');
			    			flag = true;
			    		}

			            if(flag){
			                Swal.showValidationMessage('Highlighted fields are required');
			            }
			            
			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					uploadPatient({
						avatar: $("#files").prop('files')[0],
						prefix: $("[name='prefix']").val(),
						fname: $("[name='fname']").val(),
						mname: $("[name='mname']").val(),
						lname: $("[name='lname']").val(),
						suffix: $("[name='suffix']").val(),
						birthday: $("[name='birthday']").val(),
						birth_place: $("[name='birth_place']").val(),
						gender: $("[name='gender']").val(),
						civil_status: $("[name='civil_status']").val(),
						nationality: $("[name='nationality']").val(),
						religion: $("[name='religion']").val(),
						contact: $("[name='contact']").val(),
						email: $("[name='email']").val(),
						address: $("[name='address']").val(),
						hmo_provider: $("[name='hmo_provider']").val(),
						hmo_number: $("[name='hmo_number']").val(),
						employment_status: $("[name='employment_status']").val(),
						company_name: $("[name='company_name']").val(),
						company_position: $("[name='company_position']").val(),
						sss: $("[name='sss']").val(),
						tin_number: $("[name='tin_number']").val(),
						_token: $('meta[name="csrf-token"]').attr('content')
					});
				}
			});
		}

		function del(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('transaction.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						reload();
					})
				}
			});
		}
	</script>
@endpush