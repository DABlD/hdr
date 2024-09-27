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
					url: "{{ route('datatable.user') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						where: ["role", "=", "Patient"],
						load: ['patient']
					}
				},
				columns: [
					{data: 'patient.patient_id'},
					{data: 'lname'},
					{data: 'fname'},
					{data: 'gender'},
					{data: 'birthday'},
					{data: 'contact'},
					{data: 'nationality'},
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

		function create(){
			Swal.fire({
				title: "Enter Patient Details",
				html: `
	                ${input("fname", "First Name", null, 3, 9)}
	                ${input("mname", "Middle Name", null, 3, 9)}
	                ${input("lname", "Last Name", null, 3, 9)}

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

	                ${input("contact", "Contact", null, 3, 9)}
					${input("email", "Email", null, 3, 9, 'email')}

	                ${input("address", "Address", null, 3, 9)}

	                <br>

	                ${input("hmo_provider", "HMO Provider", null, 3, 9)}
	                ${input("hmo_number", "HMO Number", null, 3, 9)}
				`,
				allowOutsideClick: false,
				allowEscapeKey: false,
				width: '800px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {
					$('[name="birthday"]').flatpickr({
						altInput: true,
						altFormat: "M j, Y",
						dateFormat: "Y-m-d",
						maxDate: moment().format("YYYY-MM-DD")
					})
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length || $('[name="gender"]').val() == "" || $('[name="civil_status"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }
			            else{
			            	let bool = false;
            				$.ajax({
            					url: "{{ route('user.get') }}",
            					data: {
            						select: "id",
            						where: ["email", $("[name='email']").val()]
            					},
            					success: result => {
            						result = JSON.parse(result);
            						if(result.length){
            			    			Swal.showValidationMessage('Email already used');
	            						setTimeout(() => {resolve()}, 500);
            						}
            					}
            				});
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					$.ajax({
						url: "{{ route('patient.store') }}",
						type: "POST",
						data: {
							fname: $("[name='fname']").val(),
							mname: $("[name='mname']").val(),
							lname: $("[name='lname']").val(),
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
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: () => {
							ss("Success");
							reload();
						}
					})
				}
			});
		}

		function showDetails(user){
			Swal.fire({
				title: "Patient Information",
				html: `
					<br>
	                <div class="row">

	                	<section class="col-lg-4">
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

                    	<section class="col-lg-8">
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
                        					<span class="pInfo">${user.fname}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Middle Name</span>
                        					<br>
                        					<span class="pInfo">${user.mname}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Last Name</span>
                        					<br>
                        					<span class="pInfo">${user.lname}</span>
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
                        				<div class="col-md-4">
                        					<span class="label">Contact</span>
                        					<br>
                        					<span class="pInfo">${user.contact}</span>
                        				</div>
                        			</div>

                        			<br>
                        			
                        			<div class="row">
                        				<div class="col-md-12">
                        					<span class="label">Email</span>
                        					<br>
                        					<span class="pInfo">${user.email}</span>
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
				`,
				width: '1000px',
				confirmButtonText: 'Ok',
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

		function del(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('patient.delete') }}",
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