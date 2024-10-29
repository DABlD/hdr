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
                            Company Package
                        </h3>
                        
                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Package" onclick="createPackage('Package')">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3>
                    </div>


                    <div class="card-body table-responsive">
	                    <select id="fCompany" style="width: 200px;">
	                    </select>

	                    <br>
	                    <br>

                    	<table id="PackageTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
                    				<th>Amount</th>
                    				<th style="width: 130px;">Actions</th>
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
                            <i class="fas fa-building mr-1"></i>
                            Companies
                        </h3>
                        
                        <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Company" onclick="createCompany()">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="companyTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
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
                            History
                        </h3>
                        
                        {{-- <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Laboratory" onclick="createPackage('Laboratory')">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3> --}}
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="LaboratoryTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
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
                            Subjective
                        </h3>
                        
                        {{-- <h3 class="float-right">
                            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Laboratory" onclick="createPackage('Laboratory')">
                                <i class="fas fa-plus fa-2xl"></i>
                            </a>
                        </h3> --}}
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="SubjectiveTable" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>Name</th>
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
                            Exam
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
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

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
	<script src="{{ asset('js/select2.min.js') }}"></script>

	<script>
		var sltpc = null;
		var sltpc2 = null;
		var fPackageName = null;
		var fCompany = null;

		$(document).ready(()=> {
			loadCompanies();
			loadPackages();
		});

		function loadCompanies(){
			$.ajax({
				url: '{{ route('user.get') }}',
				data: {
					select: "*",
					where: ["role", "Company"]
				},
				success: result => {
					result = JSON.parse(result);

					let companyString = '<option>Filter by Company</option>';
					let companyTableString = null;

					result.forEach(company => {
						companyString += `
							<option value="${company.fname}">${company.fname}</option>
						`;

						companyTableString += `
							<tr>
								<td>${company.fname}</td>
								<td>
									<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete" onclick="deleteCompany(${company.id})">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
						`;
					});

					$('#fCompany').html(companyString);
					$('#fCompany').select2();

					$('#fCompany').on('change', e => {
						fCompany = e.target.value;
						loadPackages();
						clearPackageView();
					});

					$('#companyTable tbody').html(companyTableString ?? "<tr><td colspan='2'>No Company Added</td></tr>")
				}
			});
		}

		function createCompany(){
			Swal.fire({
				title: "Enter Company Details",
				html: `
					${input('fname', 'Name', null, 3, 9)}
					<br>
					${input('username', 'Username', null, 3, 9)}
					${input('password', 'Password', null, 3, 9, 'password')}
				`,
				preConfirm: () => {
					let name = $('[name="fname"]').val();
					let username = $('[name="username"]').val();
					let password = $('[name="password"]').val();

					if(name == "" || password == "" || username == ""){
						Swal.showValidationMessage("Fill all details");
					}
				}
			}).then(result => {
				if(result.value){
					$.ajax({
						url: "{{ route('user.store') }}",
						type: "POST",
						data: {
							fname: $('[name="fname"]').val(),
							role: "Company",
							username: $('[name="username"]').val(),
							password: $('[name="password"]').val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						},
						success: result => {
							ss("Successfully Added Company");
							loadCompanies();
							loadPackages();
						}
					})
				}
			})
		}

		function deleteCompany(id){
			sc("Confirmation", "Are you sure you want to delete?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('user.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						loadCompanies();
						loadPackages();
					})
				}
			});
		}

		// PACKAGES FUNCTIONS
		function loadPackages(){
			$.ajax({
				url: '{{ route('package.get') }}',
				data: {
					select: '*',
					where: ['company', fCompany],
					group: 'type',
					getLaboratory: true
				},
				success: result => {
					result = JSON.parse(result);
					
					let packages = result.Package;
					let labs = result.Laboratory;
					let subs = result.Subjective;

					let pString = "";

					if(fCompany == null){
						pString = `
							<tr>
								<td colspan="3">Select Company First</td>
							</tr>
						`;
					}
					else if(packages){
						packages.forEach(a => {
							pString += `
								<tr>
									<td>${a.name}</td>
									<td>₱${numeral(a.amount).format("0,0")}</td>
									<td>
										<a class="btn btn-success btn-sm" data-toggle="tooltip" title="View" onclick="viewPackage(${a.id}, '${a.type}', '${a.name}')">
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
									<td>
										<a class="btn btn-success btn-sm" data-toggle="tooltip" title="View" onclick="viewPackage(${a.id}, '${a.type}', '${a.name}')">
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

					let sString = "";

					if(subs){
						subs.forEach(a => {
							sString += `
								<tr>
									<td>${a.name}</td>
									<td>
										<a class="btn btn-success btn-sm" data-toggle="tooltip" title="View" onclick="viewPackage(${a.id}, '${a.type}', '${a.name}')">
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
						sString = `
							<tr>
								<td colspan="3">No Subjective</td>
							</tr>
						`;
					}
					$('#SubjectiveTable tbody').html(sString);
				}
			})
		}

		function createPackage(type){
			Swal.fire({
				title: 'Input Package Details',
				html: `
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Company
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="company" class="form-control">
					        	<option value="">Select Company</option>
					        </select>
					    </div>
					</div>

					${input('name', 'Name', null, 3, 9)}
					${input('amount', 'Amount', null, 3, 9, 'number', 'min=0')}
				`,
				showCancelButton: true,
				cancelButtonColor: errorColor,
				didOpen: () => {
					$.ajax({
						url: "{{ route('user.get') }}",
						data: {
							select: "fname",
							where: ["role", "Company"]
						},
						success: result => {
							result = JSON.parse(result);

							result.forEach(company => {
								$('[name="company"]').append(`
									<option value="${company.fname}">${company.fname}</option>
								`);
							});

							$('[name="company"]').select2();
						}
					})
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;
				    	
			            if($('.swal2-container input:placeholder-shown').length || $('[name="company"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					storePackage($('[name="name"]').val(), $('[name="amount"]').val(), type, $('[name="company"]').val());
				}
			})
		}

		function storePackage(name, amount, type, company){
			$.ajax({
				url: '{{ route('package.store') }}',
				type: "POST",
				data: {
					name: name,
					amount: amount,
					type: type,
					company: company,
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: result => {
					ss("Success");
					setTimeout(() => {
						swal.showLoading();
						setTimeout(() => {
							loadPackages();
							loadCompanies();
							swal.close();
						}, 1000);
					}, 1500);
				}
			})
		}

		function editPackage(id){
			$.ajax({
				url: "{{ route('package.get') }}",
				data: {
					select: '*',
					where: ["id", id]
				},
				success: package => {
					package = JSON.parse(package)[0];

					Swal.fire({
						title: 'Package Details',
						html: `
							${input('name', 'Name', package.name, 3, 9)}
							${input('company', 'Company', package.company, 3, 9)}
							${input('amount', 'Amount', package.amount, 3, 9, 'number', 'min=0')}
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
							update({
								url: "{{ route('package.update') }}",
								data: {
									id: id,
									name: $('[name="name"]').val(),
									company: $('[name="company"]').val(),
									amount: $('[name="amount"]').val()
								},
								message: "Success"
							}, () => {
								loadPackages();
								loadCompanies();
							})
						}
					})
				}
			});
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
						loadCompanies();
					})
				}
			});
		}

		// QUESTION FUNCTIONS
		function viewPackage(id, type, name = fPackageName){
			sltpc = id;
			sltpc2 = type;
			fPackageName = name;
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

					if(result[""] != undefined){
						for (let [k, v] of Object.entries(result[""])) {
						    string += `
						    	<div class="row">
						    		<div class="col-md-12" style="text-align: left;">
							    		<b style="font-size: 1.5rem;">${v.name}</b>

							    		&nbsp;
							    		<tr>
								    		<td>
								    			<a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Inclusion" onclick="getQuestionData(${v.id})">
								    				<i class="fas fa-plus"></i>
								    			</a>
								    			<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete Category" onclick="deleteCategory(${v.id})">
								    				<i class="fas fa-trash"></i>
								    			</a>
								    		</td>
							    		</tr>
						    		</div>
						    	</div>

						    	<table class="table table-hover qtd" style="width: 100%; margin-top: 5px;">
						    		<thead>
						    			<tr>
						    				<th>Name</th>
						    				<th class="tType">Type</th>
						    				<th class="tCode">Code</th>
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
							    			<td class="tType">${temp[i].type}</td>
							    			<td class="tCode">${temp[i].code}</td>
							    			<td>
							    				<a class="btn btn-info btn-sm" data-toggle="tooltip" title="Edit Inclusion" onclick="editQuestion(${temp[i].id}, '${temp[i].name}', '${temp[i].type}', '${temp[i].code}')">
							    					<i class="fas fa-pencil"></i>
							    				</a>
							    				<a class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete Inclusion" onclick="deleteQuestion(${temp[i].id})">
							    					<i class="fas fa-trash"></i>
							    				</a>
							    			</td>
							    		</tr>
							    	`;
							    }
						    }
						    else{
						    	string += `
						    		<tr>
						    			<td colspan="3" style="text-align: center !important;">No Inclusions Added</td>
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
						$('#questions').html(
							`
								<h1 style="color: #0a73ad; text-align: left;"><b>
						    		${fPackageName}
						    	</b></h1>
						    	${string}
							`
						);
						if(sltpc2 == "Package"){
							$('.tType').hide();
						}
						else{
							$('.tCode').hide();
						}
						$('#questions').slideDown();
					}, 500);
				}
			})
		}

		function clearPackageView(){
			$('#questions').slideUp(500);
			
			setTimeout(() => {
				$('#questions').html("No Selected Package");
				$('#questions').slideDown();
			}, 500);
		}

		function addCategory(){
			if(sltpc == null){
				se('No selected package');
				return false;
			}

			Swal.fire({
				title: 'Enter Category Name',
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

		function getQuestionData(cid){
			Swal.fire({
				title: 'Enter Inclusion',
				html: `
					${input('name', 'Name', null, 3, 9)}
					${input('code', 'Code', null, 3, 9)}
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Dichotomous">Dichotomous</option>
					        	<option value="Text">Text</option>
					        	<option class="d-none" value="Inclusion"></option>
					        </select>
					    </div>
					</div>
				`,
				showCancelButton: true,
				cancelButtonColor: errorColor,
				didOpen: () => {
					if(sltpc2 == "Package"){
						$('[name="type"]').parent().parent().hide();
						$('[name="type"]').val("Inclusion").change();
					}
					else{
						$('[name="code"]').parent().parent().hide();
					}
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('[name="name"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }
			            else if(sltpc2 == "Package" && $('[name="code"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					addQuestion({
						package_id: sltpc,
						category_id: cid,
						name: $('[name="name"]').val(),
						code: $('[name="code"]').val(),
						type: sltpc2 == "Package" ? "Inclusion" : $('[name="type"]').val()
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
					viewPackage(sltpc, sltpc2);
				}
			})
		}

		function editQuestion(id, name, type, code){
			Swal.fire({
				title: 'Enter Details',
				html: `
					${input('name', 'Name', name, 3, 9)}
					${input('code', 'Code', code, 3, 9)}
					<div class="row iRow">
					    <div class="col-md-3 iLabel">
					        Type
					    </div>
					    <div class="col-md-9 iInput">
					        <select name="type" class="form-control">
					        	<option value="">Select Type</option>
					        	<option value="Dichotomous">Dichotomous</option>
					        	<option value="Text">Text</option>
					        	<option class="d-none" value="Inclusion"></option>
					        </select>
					    </div>
					</div>
				`,
				showCancelButton: true,
				cancelButtonColor: errorColor,
				didOpen: () => {
					$("[name='type']").val(type).trigger('change');

					if(sltpc2 == "Package"){
						$('[name="type"]').parent().parent().hide();
					}
					else{
						$('[name="code"]').parent().parent().hide();
					}
				},
				preConfirm: () => {
				    swal.showLoading();
				    return new Promise(resolve => {
				    	let bool = true;

			            if($('.swal2-container input:placeholder-shown').length || $('[name="type"]').val() == ""){
			                Swal.showValidationMessage('Fill all fields');
			            }

			            bool ? setTimeout(() => {resolve()}, 500) : "";
				    });
				},
			}).then(result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('question.update') }}",
						data: {
							id: id,
							name: $('[name="name"]').val(),
							type: $('[name="type"]').val(),
							code: $('[name="code"]').val(),
						},
						message: "Success"
					}, () => {
						viewPackage(sltpc, sltpc2);
					})
				}
			});
		}

		function deleteQuestion(id){
			sc("Confirmation", "Are you sure you want to delete this question?", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('question.delete') }}",
						data: {id: id},
						message: "Success"
					}, () => {
						viewPackage(sltpc, sltpc2);
					})
				}
			});
		}

		function deleteCategory(qid){
			sc("Confirmation", "Are you sure you want to delete this category? This will delete all included questions.", result => {
				if(result.value){
					swal.showLoading();
					update({
						url: "{{ route('question.delete') }}",
						data: {id: qid, category: true},
						message: "Success"
					}, () => {
						viewPackage(sltpc, sltpc2);
					})
				}
			});
		}
	</script>
@endpush