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

                        {{-- @include('users.includes.toolbar') --}}
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Date</th>
                    				<th>ID</th>
                    				<th>Last Name</th>
                    				<th>First Name</th>
                    				<th>Doctor</th>
                    				{{-- <th>Time</th> --}}
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
	<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">

	<style>
		td, th{
			text-align: center !important;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>

	<script>
		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.queued_patients') }}",
                	dataType: "json",
                	dataSrc: "",
					data: {
						select: "*",
						where: ["queued_at", "=", moment().format('YYYY-MM-DD')],
						load: ['user', 'attending_doctor']
					}
				},
				columns: [
					{data: 'updated_at', visible: false},
					{data: 'id'},
					{data: 'user.lname'},
					{data: 'user.fname'},
					{data: 'attending_doctor.fname'},
				],
        		pageLength: 25,
				order: [[0, 'desc']],
        		columnDefs: [
        			{
        			    targets: 4,
        			    render: (a,b,row) => {
        			    	let ad = row.attending_doctor;
        			        return ad.fname + " " + ad.lname;
        			    },
        			},
        		]
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
		});
	</script>
@endpush