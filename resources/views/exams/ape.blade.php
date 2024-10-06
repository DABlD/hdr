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
                            Annual Physical Examination - Patient List
                        </h3>

                        @include('exams.includes.toolbar')
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

	    #swal2-html-container tbody td{
	    	text-align: left;
	    	vertical-align: middle;
	    }
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	<script src="{{ asset('js/numeral.min.js') }}"></script>

	<script>
		var fFname = "%%";
		var fLname = "%%";

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.user') }}",
                	dataType: "json",
                	dataSrc: "",
	                data: f => {
	                    f.select = "*";
	                    f.filters = getFilters();
	                    f.where = ["role", "Patient"];
	                    f.load = ["patient"]
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
					{data: 'medical'}
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

			$('#fFname').on('change', e => {
	            e = $(e.target);
	            fFname = e.val();
	        });

			$('#fLname').on('change', e => {
	            e = $(e.target);
	            fLname = e.val();
	        });
		});

		function getFilters(){
            return {
                fFname: fFname,
                fLname: fLname,
            }
        }

        function takeExam(id){
        	$.ajax({
        		url: '{{ route('patient.get') }}',
        		data: {
        			select: "*",
        			where: ['user_id', id],
        			load: ['packages.questions', 'user', 'exams']
        		},
        		success: patient => {
        			patient = JSON.parse(patient)[0];

        			let packageString = "";

        			if(patient.packages.length){
        				patient.packages.forEach(package => {
	        				packageString += `
	        					<tr>
	        						<td>
	        							<input type="checkbox" name="sPackage" value="${package.id}">
	        							&nbsp;&nbsp;
	        							<span style="color: blue; font-weight: bold;">${package.name}</span> - 
	        							<span style="color: green;">PHP ${package.amount}</span>
	        						</td>
	        				`;

	        				let list = "";
	        				let questions = Object.groupBy(package.questions, ({ category_id }) => category_id);

	        				questions[null].forEach(category => {
		        				let inclusions = "";

		        				if(questions[category.id]){
		        					questions[category.id].forEach(question => {
		        						inclusions += `${question.name}<br>`;
		        					});

		        					list += `
		        						- <b>${category.name}</b><br>
			        					${inclusions}<br>
		        					`;
		        				}
	        				});

	        				packageString += `
	        						<td>
		        						${list}
	        						</td>
	        					</tr>
	        				`;
        				});
        			}
        			else{
        				packageString = `
	        				<tr>
	        					<td colspan="2">No Available Package</td>
	        				</tr>
	        			`;
        			}
        			
		        	Swal.fire({
		        		html: `
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
		    	                			<img src="../${patient.user.avatar}" alt="avatar" width="120" height="120">
		                    			</div>

		                    			<br>
		                    			
		                    			<div class="col-md-12 pInfo-left">
		                    				<span class="label">Patient ID</span>
		                    				<br>
		                    				<span class="pInfo">${patient.patient_id}</span>
		                    			</div>

		                    			<br>

		                    			<div class="col-md-12 pInfo-left">
		                    				<span class="label">Name</span>
		                    				<br>
		                    				<span class="pInfo">${patient.user.fname} ${patient.user.lname}</span>
		                    			</div>
		                    			
		                    			<br>
		                    			
		                    			<div class="col-md-12 pInfo-left">
		                    				<span class="label">Gender</span>
		                    				<br>
		                    				<span class="pInfo">${patient.user.gender}</span>
		                    			</div>
		                    			
		                    			<br>
		                    			
		                    			<div class="col-md-12 pInfo-left">
		                    				<span class="label">Birthday</span>
		                    				<br>
		                    				<span class="pInfo">${toDate(patient.user.birthday)}</span>
		                    			</div>
		                    			
		                    			<br>
		                    			
		                    			<div class="col-md-12 pInfo-left">
		                    				<span class="label">Age</span>
		                    				<br>
		                    				<span class="pInfo">${moment().diff(moment(patient.user.birthday), 'years')}</span>
		                    			</div>

			                        </div>
			                    </div>
			                </section>

		                	<section class="col-lg-9">
			                    <div class="card">
			                        <div class="card-header row">
			                            <div class="col-md-12">
			                                <h3 class="card-title" style="width: 100%; text-align: left;">
			                                    <i class="fas fa-box-check mr-1"></i>

			                                    Package Request

			                                </h3>
			                            </div>
			                        </div>

			                        <div class="card-body">
		                    			<table class="table table-hover table-bordered">
		                    				<thead>
		                    					<tr>
		                    						<th>Package Name</th>
		                    						<th>Inclusions</th>
		                    					</tr>
		                    				</thead>
		                    				<tbody>
		                    					${packageString}
		                    				</tbody>
		                    			</table>
			                        </div>
			                    </div>
			                </section>

			                </div>
		        		`,
						width: '1200px',
						confirmButtonText: 'Request',
						showCancelButton: true,
						cancelButtonColor: errorColor,
						cancelButtonText: 'Cancel',
						allowOutsideClick: false,
						allowEscapeKey: false,
						didOpen: () => {
							$('.pInfo').parent().css('text-align', 'left');
							$('#swal2-html-container .card-header').css('margin', "1px");
							$('#swal2-html-container .card-header').css('background-color', "#83c8e5");
							$('#swal2-html-container .card-body').css('border', "1px solid rgba(0,0,0,0.125)");
						}
		        	}).then(result => {
		        		if(result.value){
		        			let sPackage = [];

		                    $('[name="sPackage"]:checked').each((i, e) => {
		                        sPackage.push(e.value);
		                    });

		                    if(sPackage.length){
		                    	$.ajax({
		                    		url: '{{ route('patientPackage.store') }}',
		                    		type: "POST",
		                    		data: {
		                    			packages: sPackage,
		                    			uid: id,
		                    			_token: $('meta[name="csrf-token"]').attr('content')
		                    		},
		                    		success: result => {
				                    	setTimeout(() => {
		                    				swal.showLoading();
				                    	}, 100);

				                    	setTimeout(() => {
			                    			ss("Success");
					                    	setTimeout(() => {
					                    		takeExam(id);
					                    	}, 1000);
				                    	}, 2000);
		                    		}
		                    	})
		                    }
		                    else{
		                    	se('No Package Selected');
		                    }
		        		}
		        	})
        		}
        	})
        }
	</script>
@endpush