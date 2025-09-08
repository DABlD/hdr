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
                            Export Report
                        </h3>

                        @include('reports.includes.toolbar')
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
	        $('#fCompany').select2();

	        $('#fFrom').val(fFrom);
	        $('#fTo').val(fTo);

			$('#fFrom, #fTo').flatpickr({
			    altInput: true,
			    altFormat: 'F j, Y',
			    dateFormat: 'Y-m-d',
			});
		});

		function exportReport(){
			let type = $('#fExportType').val();

            let data = {
                from: $('#fFrom').val(),
                to: $('#fTo').val(),
                company: $('#fCompany').val(),
            };

            if(type == "exam"){
            	data.filters = {
            		fCompany: $('#fCompany').val(),
            		fType: $('#fExportType option:selected').data('type'),
            		from: data.from,
            		to: data.to,
            	};
            	data.type = data.filters.fType;
            }

            window.location.href = `${window.location.origin}/report/${type}?` + $.param(data);
		}
	</script>
@endpush