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
		});
		
		function getFilters(){
			return {
			    fCompany: fCompany,
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
			                <input type="file" id="fileInput" class="form-control-file border rounded p-2">
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
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

				    	let company = $('#company').val();
				    	let package = $('#package').val();
				    	let pax = $('[name="pax"]').val();

				    	if(company == "" || package == "" || pax == ""){
				    		Swal.showValidationMessage("All fields is required");
				    	}
			            
			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					
					$.ajax({
						url: "{{ route('transaction.store') }}",
						type: "POST",
						data: {
							company: $('#company').val(),
							package_id: $('#package').val(),
							pax: $('[name="pax"]').val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: result => {
							ss("Successfully added a transaction");
							reload();
						}
					})
				}
			});
		}
	</script>
@endpush