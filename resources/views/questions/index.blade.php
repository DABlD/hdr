@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
        	{{-- PACKAGES --}}
            <section class="col-lg-4 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Packages
                        </h3>
                        
                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Package" onclick="createPackage('Package')">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="PackageTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
                    				<th>Amount</th>
                    				<th>Actions</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    		</tbody>
                    	</table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Laboratory
                        </h3>
                        
                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Laboratory" onclick="createPackage('Laboratory')">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="LaboratoryTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
                    				<th>Amount</th>
                    				<th>Actions</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    		</tbody>
                    	</table>
                    </div>
                </div>
            </section>

            {{-- QUESTIONS --}}
            <section class="col-lg-8 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Questionnaire
                        </h3>
                        
                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" id="addCategory" title="Add Category" onclick="addCategory()">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3>
                    </div>

                    <div class="card-body table-responsive" id="questions">
                    	No Selected Package
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
		.card-body table td, .card-body table th, #questions{
			text-align: center;
		}

		.qtd td, .qtd th{
			text-align: left !important;
		}

		.qtd th:nth-child(1){
			width: 50%;
		}

		.qtd th:nth-child(2){
			width: 25%;
		}

		.qtd th:nth-child(3){
			width: 25%;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
	<script src="{{ asset('js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('js/numeral.min.js') }}"></script>

	<script>
		var sltpc = null;

		$(document).ready(()=> {
			loadPackages();
		});

		// PACKAGES FUNCTIONS
		function loadPackages(){
			$.ajax({
				url: '{{ route('package.get') }}',
				data: {
					select: '*',
					group: 'type'
				},
				success: result => {
					result = JSON.parse(result);
					
					let packages = result.Package;
					let labs = result.Laboratory;

					let pString = "";

					if(packages){
						packages.forEach(a => {
							pString += `
								<tr>
									<td>${a.name}</td>
									<td>₱${numeral(a.amount).format("0,0")}</td>
									<td>
										<a class="btn btn-success btn-sm" data-toggle="tooltip" title="View" onclick="viewPackage(${a.id})">
											<i class="fas fa-search"></i>
										</a>
										<a class="btn btn-info btn-sm" data-toggle="tooltip" title="Edit" onclick="editPackage(${a.id})">
											<i class="fas fa-pencil"></i>
										</a>
										<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete" onclick="deletePackage(${a.id})">
											<i class="fas fa-trash"></i>
										</a>
									</td>
								</tr>
							`;
						});
					}
					else{
						pString = `
							<tr>
								<td colspan="3">No Packages</td>
							</tr>
						`;
					}
					$('#PackageTable tbody').html(pString);

					let lString = "";

					if(labs){
						labs.forEach(a => {
							lString += `
								<tr>
									<td>${a.name}</td>
									<td>₱${numeral(a.amount).format("0,0")}</td>
									<td>
										<a class="btn btn-success btn-sm" data-toggle="tooltip" title="View" onclick="viewPackage(${a.id})">
											<i class="fas fa-search"></i>
										</a>
										<a class="btn btn-info btn-sm" data-toggle="tooltip" title="Edit" onclick="editPackage(${a.id})">
											<i class="fas fa-pencil"></i>
										</a>
										<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete" onclick="deletePackage(${a.id})">
											<i class="fas fa-trash"></i>
										</a>
									</td>
								</tr>
							`;
						});
					}
					else{
						lString = `
							<tr>
								<td colspan="3">No Laboratories</td>
							</tr>
						`;
					}
					$('#LaboratoryTable tbody').html(lString);
				}
			})
		}

		function createPackage(type){
			Swal.fire({
				title: 'Input Package Details',
				html: `
					${input('name', 'Name', null, 3, 9)}
					${input('amount', 'Amount', null, 3, 9, 'number', 'min=0')}
				`,
				showCancelButton: true,
				cancelButtonColor: errorColor,
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					storePackage($('[name="name"]').val(), $('[name="amount"]').val(), type);
				}
			})
		}

		function storePackage(name, amount, type){
			$.ajax({
				url: '{{ route('package.store') }}',
				type: "POST",
				data: {
					name: name,
					amount: amount,
					type: type,
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: result => {
					ss("Success");
					setTimeout(() => {
						swal.showLoading();
						setTimeout(() => {
							loadPackages();
							swal.close();
						}, 1000);
					}, 1500);
				}
			})
		}

		function deletePackage(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('package.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						loadPackages();
					})
				}
			});
		}

		// QUESTION FUNCTIONS
		function viewPackage(id){
			sltpc = id;
			$('#addCategory').show();

			$.ajax({
				url: "{{ route('question.get') }}",
				data: {
					select: '*',
					where: ["package_id", id],
					group: ['category_id']
				},
				success: result => {
					result = JSON.parse(result);
					let keys = Object.keys(result);
					let categories = result[keys[keys.length-1]];

					let string = "";

					// console.log(result, keys, categories, result[""]);

					if(result[""] != undefined){
						for (let [k, v] of Object.entries(result[""])) {
						    string += `
						    	<div class="row">
						    		<h3><b>${v.name}</b></h3>
						    	</div>
						    	<table class="table table-hover qtd" style="width: 100%;">
						    		<thead>
						    			<tr>
						    				<th>Name</th>
						    				<th>Type</th>
						    				<th>Actions</th>
						    			</tr>
						    		</thead>
						    		<tbody>
						    `;

						    let temp = result[v.id];

						    if(temp){
							    for(let i = 0; i < temp.length; i++){
							    	string += `
							    		<tr>
							    			<td>${temp[i].name}</td>
							    			<td>${temp[i].type}</td>
							    			<td>test</td>
							    		</tr>
							    	`;
							    }
						    }
						    else{
						    	string += `
						    		<tr>
						    			<td colspan="3" style="text-align: center !important;">No Questions Added</td>
						    		</tr>
						    	`;
						    }


						    string += "</tbody></table><br>";
						}
					}
					else{
						string = `
							No Categories Added.
						`;
					}

					$('#questions').slideUp(500);
					
					setTimeout(() => {
						$('#questions').html(string);
						$('#questions').slideDown();
					}, 500);


					// for(i = 0; i < (keys.length - 1); i++){
					// 	console.log(keys[i]);
					// }
				}
			})
		}

		function addCategory(){
			if(sltpc == null){
				se('No selected package');
				return false;
			}

			Swal.fire({
				title: 'Enter Details',
				html: `
					${input('name', 'Name', null, 4, 8)}
				`,
				showCancelButton: true,
				cancelButtonColor: errorColor,
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					addQuestion({
						package_id: sltpc,
						name: $('[name="name"]').val(),
						type: 'Category',
					});
				}
			});
		}

		function addQuestion(data){
			$.ajax({
				url: "{{ route('question.store') }}",
				type: "POST",
				data: {
					...data, 
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: () => {
					ss("Success");
					viewPackage(sltpc);
				}
			})
		}
	</script>
@endpush