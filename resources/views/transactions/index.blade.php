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

                        @include('transactions.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Company</th>
                    				<th>Package</th>
                    				<th>Total # of Transaction</th>
                    				<th>Availed Transaction</th>
                    				<th>Pending Transaction</th>
                    				<th>Status</th>
                    				<th>Created At</th>
                    				<th>Action</th>
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
		var fFrom = moment().subtract(3,'months').format("YYYY-MM-DD");
		var fTo = moment().format("YYYY-MM-DD");

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
					{data: 'created_at'},
					{data: 'actions'}
				],
        		pageLength: 10,
	            columnDefs: [
	                {
	                    targets: [2,3,4],
	                    className: "text-center"
	                },
	                {
	                    targets: 6,
	                    render: created_at => {
	                        return toDateTime(created_at)
	                    },
	                }
	            ],
	            order: [[6, 'desc']]
			});

			$('#fCompany').on('change', e => {
	            e = $(e.target);
	            fCompany = e.val();
	            reload();
	        });

	        function continuesReload(){
	        	setTimeout(() => {
	        		// if(!Swal.getPopup() && $('.flatpickr-calendar:visible').length == 0){
	        			reload(null, false);
	        		// 	swal.close();
	        		// }
	        		continuesReload();
	        	}, 5000);
	        }
	        continuesReload();

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
				title: "Enter Patient Details",
				html: `
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Company
					    </div>
					    <div class="col-md-9 iLabel">
							<select id="company" class="form-control">
								<option value="">Select Company</option>
								@foreach($companies as $company)
									<option value="{{ $company }}">{{ $company }}</option>	
								@endforeach
							</select>
						</div>
					</div>

					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Package
					    </div>
					    <div class="col-md-9 iLabel">
							<select id="package" class="form-control" disabled>
								<option value="">Select Company First</option>
							</select>
						</div>
					</div>

					${input('pax', 'Pax', null, 3, 9)}
				`,
				allowOutsideClick: false,
				allowEscapeKey: false,
				width: '800px',
				confirmButtonText: 'Save',
				showCancelButton: true,
				cancelButtonColor: errorColor,
				cancelButtonText: 'Cancel',
				didOpen: () => {
					$('#company, #package').select2();

					$('#company').on('change', e => {
						$('#package').removeAttr('disabled');

						$.ajax({
							url: "{{ route('package.get') }}",
							data: {
								select: ['id', 'name'],
								where: ['company', e.target.value]
							},
							success: result => {
								result = JSON.parse(result);

								let packageString = "";
								result.forEach(package => {
									packageString += `
										<option value="${package.id}">${package.name}</option>
									`;
								});

								$('#package').html(
									`
										<option value="">Select Package</option>
										${packageString}
									`
								);
							}
						})
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

		function complete(id){
			sc("Confirmation", "Are you sure you want to complete transaction?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('transaction.update') }}",
						data: {id: id, status: "Completed"},
						message: "Success"
					}, () => {
						reload();
					})
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

		function exportCensus(){
            let data = {
                from: fFrom,
                to: fTo,
                company: fCompany
            };

            window.location.href = "{{ route('report.exportTransactions') }}?" + $.param(data);
		}
	</script>
@endpush