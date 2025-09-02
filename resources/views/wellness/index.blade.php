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

                        @include('wellness.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>#</th>
                    				<th>Company</th>
                    				<th>Created At</th>
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
	<link rel="stylesheet" href="{{ asset('css/summernote.min.css') }}">

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
	<script src="{{ asset('js/summernote.min.js') }}"></script>

	<script>
		var fCompany = "%%";
		var fFrom = moment().subtract(3,'months').format("YYYY-MM-DD");;
		var fTo = moment().format("YYYY-MM-DD");

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.wellness') }}",
                	dataType: "json",
                	dataSrc: "",
	                data: f => {
	                    f.select = ["*"];
	                    f.filters = getFilters();
	                }
				},
				columns: [
					{data: 'id'},
					{data: 'company'},
					{data: 'created_at'},
					{data: 'actions'}
				],
        		pageLength: 10,
	            columnDefs: [
	                {
	                    targets: 2,
	                    render: created_at => {
	                        return toDateTime(created_at)
	                    },
	                }
	            ],
	            order: [[2, 'desc']]
			});

			$('#fCompany').on('change', e => {
	            e = $(e.target);
	            fCompany = e.val();
	            reload();
	        });

	        $('#fCompany').select2();

	        $('#fFrom').val(fFrom);
	        $('#fTo').val(fTo);
	        
			$('#fFrom, #fTo').flatpickr({
			    altInput: true,
			    altFormat: 'F j, Y',
			    dateFormat: 'Y-m-d',
			});

			$('#fFrom').on('change', e => {
	            e = $(e.target);
	            fFrom = e.val();
	            reload();
	        });

			$('#fTo').on('change', e => {
	            e = $(e.target);
	            fTo = e.val();
	            reload();
	        });
		});
		
		function getFilters(){
			return {
			    fCompany: fCompany,
                fFrom: fFrom,
                fTo: fTo
			}
		}

		function create(){
			Swal.fire({
				title: "Wellness program and recommendation details",
				html: `
					<div class="container-fluid text-left">
			            <div class="form-group mb-3">
			                <label for="company">Company</label>
							<select id="company" class="form-control">
								<option value="">Select Company</option>
								@foreach($companies as $company)
									<option value="{{ $company }}">{{ $company }}</option>	
								@endforeach
							</select>
			            </div>
			            <div class="form-group">
			                <label for="recommendation">Recommendation</label>
			                <div id="recommendation"></div>
			            </div>
			            <div class="form-group mb-3">
			                <label for="fileInput">Attach File</label>
			                <input type="file" id="fileInput" class="form-control-file border rounded p-2" multiple>
			                <small class="form-text text-muted">Allowed: PDF, DOCX, Images</small>
			            </div>
			        </div>
				`,
				allowOutsideClick: false,
				allowEscapeKey: false,
				width: '1000px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				position: 'top',
				didOpen: () => {
					$('#company').select2();
					$('#recommendation').summernote({
						height: 400,
                		focus: true,
                		toolbar: [
                		    ['style', ['style']],
                		    ['font', ['bold', 'italic', 'underline', 'clear']],
                		    ['fontname', ['fontname']],
                		    ['fontsize', ['fontsize']],
                		    ['color', ['color']],
                		    ['para', ['ul', 'ol', 'paragraph']],
                		    ['height', ['height']],
                		    ['insert', ['link', 'picture']], // 👈 picture here
                			['view', ['help']]
                		]
					});
				},
				preConfirm: () => {
				    Swal.showLoading();
				    return new Promise((resolve) => {
				        let bool = true;

				        let company = $('#company').val();
				        let recommendation = $('#recommendation').summernote('code'); // assuming textarea
				        let fileInput = $('#fileInput')[0].files[0];

				        if (company === "" || recommendation === "") {
				            Swal.showValidationMessage("Company and Recommendation are required");
				            bool = false;
				        }

				        bool 
				            ? setTimeout(() => {
				                resolve({
				                    company: company,
				                    recommendation: recommendation,
				                    fileInput: fileInput
				                });
				            }, 500) 
				            : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					
					const formData = new FormData();
					formData.append('company', result.value.company);
					formData.append('recommendation', result.value.recommendation);
					const files = document.getElementById('fileInput').files;
					for (let i = 0; i < files.length; i++) {
					    formData.append('files[]', files[i]); // note the [] in name
					}

					formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

					// AJAX request
					fetch('{{ route('wellness.store') }}', {
					    method: 'POST',
					    body: formData
					})
					.then(response => response.json())
					.then(data => {
					    ss("Successfully saved");
						reload();
					})
					.catch(error => {
					    Swal.fire('Error!', 'Something went wrong.', 'error');
					});
				}
			});
		}

		function view(id){
            $.ajax({
                url: "{{ route('wellness.get') }}",
                data: {
                    select: '*',
                    where: ['id', id]
                },
                success: wellness => {
                    wellness = JSON.parse(wellness)[0];
                    showDetails(wellness);
                }
            })
		}


		function showDetails(wellness){
			Swal.fire({
				title: "Wellness program and recommendation details",
				html: `
					<div class="container-fluid text-left">
			            <div class="form-group mb-3">
			                <label for="company">Company</label>
							<select id="company" class="form-control">
								<option value="">Select Company</option>
								@foreach($companies as $company)
									<option value="{{ $company }}">{{ $company }}</option>	
								@endforeach
							</select>
			            </div>
			            <div class="form-group">
			                <label for="recommendation">Recommendation</label>
			                <div id="recommendation">
			                	${wellness.recommendation}
			                </div>
			            </div>
			            <div class="form-group mb-3">
			                <label for="fileInput">Attach File</label>
			                <input type="file" id="fileInput" class="form-control-file border rounded p-2" multiple>
			                <small class="form-text text-muted">Allowed: PDF, DOCX, Images</small>
			            </div>
			        </div>
				`,
				allowOutsideClick: false,
				allowEscapeKey: false,
				width: '1000px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				position: 'top',
				didOpen: () => {
					$('#company').select2();
					$('#recommendation').summernote({
						height: 400,
                		focus: true,
                		toolbar: [
                		    ['style', ['style']],
                		    ['font', ['bold', 'italic', 'underline', 'clear']],
                		    ['fontname', ['fontname']],
                		    ['fontsize', ['fontsize']],
                		    ['color', ['color']],
                		    ['para', ['ul', 'ol', 'paragraph']],
                		    ['height', ['height']],
                		    ['insert', ['link', 'picture']], // 👈 picture here
                			['view', ['help']]
                		]
					});

					$('#company').val(wellness.company).trigger('change');
				},
				preConfirm: () => {
				    Swal.showLoading();
				    return new Promise((resolve) => {
				        let bool = true;

				        let company = $('#company').val();
				        let recommendation = $('#recommendation').summernote('code'); // assuming textarea
				        let fileInput = $('#fileInput')[0].files[0];

				        if (company === "" || recommendation === "") {
				            Swal.showValidationMessage("Company and Recommendation are required");
				            bool = false;
				        }

				        bool 
				            ? setTimeout(() => {
				                resolve({
				                    company: company,
				                    recommendation: recommendation,
				                    fileInput: fileInput
				                });
				            }, 500) 
				            : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					
					const formData = new FormData();
					formData.append('id', wellness.id);
					formData.append('company', result.value.company);
					formData.append('recommendation', result.value.recommendation);
					const files = document.getElementById('fileInput').files;
					for (let i = 0; i < files.length; i++) {
					    formData.append('files[]', files[i]); // note the [] in name
					}

					formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

					// AJAX request
					fetch('{{ route('wellness.update') }}', {
					    method: 'POST',
					    body: formData
					})
					.then(response => response.json())
					.then(data => {
					    ss("Successfully saved");
						reload();
					})
					.catch(error => {
					    Swal.fire('Error!', 'Something went wrong.', 'error');
					});
				}
			});
		}

		function send(id){
            let data = {};

            data.id = id;
 			window.open(`{{ route('wellness.sendToPortal') }}?` + $.param(data), '_blank');
		}
	</script>
@endpush