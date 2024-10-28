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
                    				<th>Created At</th>
                    				<th>Company</th>
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
	<script src="{{ asset('js/numeral.min.js') }}"></script>
	<script src="{{ asset('js/qrcode.min.js') }}"></script>

	<script>
		var fCompany = "%%";

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.user') }}",
                	dataType: "json",
                	dataSrc: "",
	                data: f => {
	                    f.select = ["users.*", "p.company_name"];
	                    f.filters = getFilters();
	                    f.where = ["role", "Patient"];
	                    f.load = ["patient"];
	                    f.join = "patients"
	                }
				},
				columns: [
					{data: 'created_at', visible: false},
					{data: 'patient.company_name'},
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
	                    targets: 6,
	                    render: birthday => {
	                        return birthday ? toDate(birthday) + " " + `(${moment().diff(birthday, 'years')})` : "-";
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

		async function updateAvatar(id){
            let formData = new FormData();

            formData.append('id', id);
            formData.append('avatar', $("#files").prop('files')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            await fetch('{{ route('user.update') }}', {
                method: "POST", 
                body: formData
            });
		}

		function showDetails(user){
			Swal.fire({
				title: "Patient Information",
				html: `
					<br>
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

                    	<section class="col-lg-9">
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
                        					<span class="pInfo">${user.fname ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Middle Name</span>
                        					<br>
                        					<span class="pInfo">${user.mname ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Last Name</span>
                        					<br>
                        					<span class="pInfo">${user.lname ?? "-"}</span>
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

	                <div class="row">
	                	<section class="col-lg-3">
		                    <div class="card">
		                        <div class="card-header row">
		                            <div class="col-md-12">
		                                <h3 class="card-title" style="width: 100%; text-align: left;">
		                                    <i class="fas fa-phone mr-1"></i>

		                                    Contact Information

		                                </h3>
		                            </div>
		                        </div>

		                        <div class="card-body">
	                    			
	                    			<div class="col-md-12 pInfo-left">
	                    				<span class="label">Email</span>
	                    				<br>
	                    				<span class="pInfo">${user.email ?? "-"}</span>
	                    			</div>

	                    			<br>

	                    			<div class="col-md-12 pInfo-left">
	                    				<span class="label">Contact</span>
	                    				<br>
	                    				<span class="pInfo">${user.contact ?? "-"}</span>
	                    			</div>
	                    			
	                    			<br>
	                    			
	                    			<div class="col-md-12 pInfo-left">
	                    				<span class="label">Mother's Name</span>
	                    				<br>
	                    				<span class="pInfo">${user.patient.mothers_name ?? "-"}</span>
	                    			</div>
	                    			
	                    			<br>
	                    			
	                    			<div class="col-md-12 pInfo-left">
	                    				<span class="label">Father's Name</span>
	                    				<br>
	                    				<span class="pInfo">${user.patient.fathers_name ?? "-"}</span>
	                    			</div>
	                    			
	                    			<br>
	                    			
	                    			<div class="col-md-12 pInfo-left">
	                    				<span class="label">Guardian's Name</span>
	                    				<br>
	                    				<span class="pInfo">${user.patient.guardians_name ?? "-"}</span>
	                    			</div>

		                        </div>
		                    </div>
		                </section>

		                <section class="col-lg-9">
    	                    <div class="card">
    	                        <div class="card-header row">
    	                            <div class="col-md-12">
    	                                <h3 class="card-title" style="width: 100%; text-align: left;">
    	                                    <i class="fas fa-briefcase mr-1"></i>

    	                                    Employment Information

    	                                </h3>
    	                            </div>
    	                        </div>

    	                        <div class="card-body">

                        			<div class="row">
                        				<div class="col-md-4">
                        					<span class="label">Employment Status</span>
                        					<br>
                        					<span class="pInfo">${user.patient.employment_status ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Company Name</span>
                        					<br>
                        					<span class="pInfo">${user.patient.company_name ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">Position</span>
                        					<br>
                        					<span class="pInfo">${user.patient.company_position ?? "-"}</span>
                        				</div>
                        			</div>

                        			<br>

                        			<div class="row">
                        				<div class="col-md-4">
                        					<span class="label">Contact</span>
                        					<br>
                        					<span class="pInfo">${user.patient.company_contact ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">SSS</span>
                        					<br>
                        					<span class="pInfo">${user.patient.sss ?? "-"}</span>
                        				</div>
                        				<div class="col-md-4">
                        					<span class="label">TIN</span>
                        					<br>
                        					<span class="pInfo">${user.patient.tin_number ?? "-"}</span>
                        				</div>
                        			</div>

    	                        </div>
    	                    </div>
    	                </section>
	                </div>
				`,
				width: '1200px',
				confirmButtonText: 'OK',
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

		var historyString = "";
		function medicalHistory(id){
			$.ajax({
				url: "{{ route("patientPackage.get") }}",
				data: {
					select: "*",
					where: ["user_id", id],
					where2: ["package_id", 1],
					load: ["package"]
				},
				success: result => {
					result = JSON.parse(result)[0];

					if(result){
						generateHistoryTable(result.package_id);
						swal.showLoading();

						setTimeout(() => {
							Swal.fire({
								title: "Personal Medical History",
								html: `
									${historyString}
								`,
								showClass: {backdrop: 'swal2-noanimation',popup: '',icon: ''},
								hideClass: {popup: '',},
								width: "1500px",
								allowOutsideClick: false,
								allowEscapeKey: false,
								confirmButtonText: 'Save',
								showCancelButton: true,
								cancelButtonColor: errorColor,
								cancelButtonText: 'Cancel',
								didOpen: () => {
									let qwa = result.question_with_answers;

									if(qwa){
										qwa = JSON.parse(qwa);

										qwa.forEach(qwa => {
											let type = $(`.answer[data-id="${qwa.id}"]`).data('type');

											if(type == "Dichotomous"){
												$(`[name="rb${qwa.id}"][value="${qwa.answer}"]`).click();
											}
											else if(type == "Text"){
												$(`.answer input[data-id="${qwa.id}"]`).val(qwa.answer);
											}

											$(`.remark[data-id="${qwa.id}"]`).val(qwa.remark);
										});
									}
								},
								preConfirm: () => {
									let array = [];

									let answers = $('td.answer');
									let remarks = $('input.remark');

									let len = $('td.answer').length;

									for(let i = 0; i < len; i++){
										let id = answers[i].dataset.id;
										let type = answers[i].dataset.type;
										let answer = "";

										if(type == "Dichotomous"){
											answer = $(`[name="rb${id}"]:checked`).val() ?? null;
										}
										else if(type == "Text"){
											answer = $(`.answer input[data-id="${id}"]`).val();
										}

										array.push({
											id: id,
											answer: answer,
											remark: $(`.remark[data-id="${id}"]`).val()
										});
									}

									swal.showLoading();
									update({
										url: "{{ route("patientPackage.update") }}",
										data: {
											id: result.id,
											question_with_answers: JSON.stringify(array)
										},
										message: "Saved"
									}, () => {
										medicalHistory(id);
									})
								},
							})
						}, 1500);
					}
					else{
						se("No Medical History Record. Create One First");
						$.ajax({
							url: "{{ route('patientPackage.store') }}",
							type: "POST",
							data: {
								uid: id,
								packages: [1],
								type: "PEE",
								_token: $('meta[name="csrf-token"]').attr('content')
							},
							success: result => {
								if(result){
									setTimeout(() => {
										medicalHistory(id);
									}, 800);
								}
							}
						})
					}
				}
			})
		}

		function generateHistoryTable(packageId){
			$.ajax({
				url: "{{ route('question.get') }}",
				data: {
					select: "*",
					where: ["package_id", packageId],
					group: ['category_id']
				},
				success: questions => {
					questions = JSON.parse(questions);

					let keys = Object.keys(questions);
					let categories = questions[keys[keys.length-1]];

					let string = "";

					for (let [k, v] of Object.entries(questions[""])) {
					    string += `
					    	<div class="row">
					    		<div class="col-md-12" style="text-align: left;">
						    		<b style="font-size: 1.5rem;">${v.name}</b>
					    		</div>
					    	</div>

					    	<table class="table table-hover qtd" style="width: 100%; margin-top: 5px; text-align: left;">
					    		<thead>
					    			<tr>
					    				<th style="width: 60%;">Name</th>
					    				<th style="width: 10%;" class="answer">Answer</th>
					    				<th style="width: 30%;" class="remark">Remark</th>
					    			</tr>
					    		</thead>
					    		<tbody>
					    `;

					    let temp = questions[v.id];

					    if(temp){
						    for(let i = 0; i < temp.length; i++){
						    	let answer = "";

						    	if(temp[i].type == "Text"){
						    		answer = `
						    			<input type="text" class="form-control" data-id="${temp[i].id}">
						    		`;
						    	}
						    	else if(temp[i].type == "Dichotomous"){
						    		answer = `
						    			<input type="radio" name="rb${temp[i].id}" value="1">Yes
						    			&nbsp;
						    			<input type="radio" name="rb${temp[i].id}" value="0">No
						    		`;
						    	}

						    	string += `
						    		<tr>
						    			<td>${temp[i].name}</td>
						    			<td class="answer" data-type="${temp[i].type}" data-id="${temp[i].id}">${answer}</td>
						    			<td>
						    				<input type="text" class="form-control remark" data-id="${temp[i].id}">
						    			</td>
						    		</tr>
						    	`;
						    }
					    }

					    string += "</tbody></table><br>";
					    historyString = string;
					}
				}
			})
		}

		function packages(id){
			$.ajax({
				url: '{{ route('patientPackage.get') }}',
				data: {
					select: "*",
					where: ['user_id', id]
				},
				success: result => {
					result = JSON.parse(result);

					let packageString = `
						<tr>
							<td colspan="3" style="text-align: center;">No Package Purchased</td>
						</tr>
					`;

					if(result.length){
						packageString = '';

						result.forEach(aPackage => {
							details = JSON.parse(aPackage.details);

							packageString += `
								<tr>
									<td>${details.name}</td>
									<td>${"₱" + numeral(details.amount).format("0,0")}</td>
									<td>${toDateTime(aPackage.created_at)}</td>
								</tr>
							`;
						});
					}

					Swal.fire({
						title: "Packages",
						html: `
							<div class="row">

								<div class="col-md-10">
									<select class="form-control" id="packageSelection">
									</select>
								</div>

								<div class="col-md-2">
									<a class="btn btn-info btn-sm" onclick="addPackage(${id})">Add</a>
								</div>

							</div>

							<br>

							<div class="row">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Package</th>
											<th>Amount</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody id="availedPackage">
										${packageString}
									</tbody>
								</table>
							</div>
						`,
						didOpen: () => {
							$.ajax({
								url: '{{ route('package.get') }}',
								data: {
									select: "*",
								},
								success: packages => {
									packages = JSON.parse(packages);

									let aPackageString = "<option value=''>No Available Package</option>";

									if(packages.length){
										aPackageString = "<option value=''>Add Package</option>";

										packages.forEach(aPackage => {
											aPackageString += `
												<option value="${aPackage.id}">${aPackage.name} - ${aPackage.amount}</option>
											`;
										});
									}

									$('#packageSelection').html(aPackageString);
									$('#packageSelection').select2();
								}
							})
						}
					})
				}
			})
		}

		function addPackage(id){
			let pid = $('#packageSelection').val();

			if(pid){
				$.ajax({
					url: '{{ route('patientPackage.store') }}',
					type: "POST",
					data: {
						user_id: 6,
						package_id: pid,
						_token: $('meta[name="csrf-token"]').attr('content')
					},
					success: result => {
						ss('Successfully added package');
						setTimeout(() => {
							packages(id);
						}, 1000);
					}
				})
			}
			else{
				se('No Selected Package');
				setTimeout(() => {
					packages(id);
				}, 1000);
			}
		}

		function qr(id){
			Swal.fire({
				title: "Scan QR",
				html: `
					<div id="qrcode" style="text-align: -webkit-center;"></div>
				`,
				didOpen: () => {
					new QRCode(document.getElementById("qrcode"), "{{ route('patient.subjective') }}?id=" + id);
				}
			})
		}
	</script>
@endpush