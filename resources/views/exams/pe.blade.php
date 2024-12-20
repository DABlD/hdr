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
                            Pre-Employment Examination - Patient List
                        </h3>

                        @include('exams.includes.toolbar')
                    </div>

                    <div class="card-body table-responsive">
                    	<table id="table" class="table table-hover" style="width: 100%;">
                    		<thead>
                    			<tr>
                    				<th>HIDDEN</th>
                    				<th>Company Name</th>
                    				<th>Patient ID</th>
                    				<th>Surname</th>
                    				<th>First Name</th>
                    				<th>Gender</th>
                    				<th>Age</th>
                    				<th>Package</th>
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
	<script src="{{ asset('js/summernote.min.js') }}"></script>

	<script>
		var fFname = "";
		var fLname = "";
		var fCompany = "%%";
		var fType = "PEE";
		var fDate = moment().format("YYYY-MM-DD");

		$(document).ready(()=> {
			var table = $('#table').DataTable({
				ajax: {
					url: "{{ route('datatable.examinees') }}",
                	dataType: "json",
                	dataSrc: "",
	                data: f => {
	                    f.select = ['exam_lists.*', 'p.company_name'];
	                    f.filters = getFilters();
	                    @if(auth()->user()->role == "Doctor")
	                    	f.where = ["doctor_id", {{ auth()->user()->id }}];
	                    @endif
	                    // f.where2 = ["type", "APE"];
	                    f.load = ["user.patient.exams"];
	                    // f.join = "patients"
	                }
				},
				columns: [
					{data: 'created_at', visible: false},
					{data: 'user.patient.company_name'},
					{data: 'user.patient.patient_id'},
					{data: 'user.lname'},
					{data: 'user.fname'},
					{data: 'user.gender'},
					{data: 'user.birthday'},
					{data: 'user.id'},
					{data: 'user.id'},
					@if(auth()->user()->role != "Doctor")
						{data: 'medical', width: "165px"}
					@else
						{data: 'medical', width: "85px"}
					@endif
				],
				order: [[0, 'desc']],
        		pageLength: 25,
	            columnDefs: [
	                {
	                    targets: 6,
	                    render: birthday => {
	                        return birthday ? toDate(birthday) + " " + `(${moment().diff(birthday, 'years')})` : "-";
	                    },
	                },
	                {
	                    targets: 7,
	                    render: (a,b,row) => {
	                    	if(row.user.patient.exams.length){
	                    		let latestPackage = "";

	                    		row.user.patient.exams.forEach(exam => {
	                    			let temp = JSON.parse(exam.details);
	                    			
	                    			if(!['Medical Examination Report', 'Personal Medical History'].includes(temp.name)){
	                    				latestPackage = temp.name;
	                    			}
	                    		});

	                    		return latestPackage;
	                    	}
	                    	else{
	                        	return "-";
	                    	}
	                    },
	                },
	                {
	                    targets: 8,
	                    render: (a,b,row) => {
	                    	if(row.user.patient.exams.length){
	                    		let amount = 0;

	                    		row.user.patient.exams.forEach(exam => {
	                    			let temp = JSON.parse(exam.details);
	                    			
	                    			if(!['Medical Examination Report', 'Personal Medical History'].includes(temp.name)){
	                    				amount += temp.amount;
	                    			}
	                    		});


	                    		return "₱" + numeral(amount).format("0,0");
	                    	}
	                    	else{
	                        	return "-";
	                    	}
	                    },
	                }
	            ],
			});

			$('#fDate').flatpickr({
			    altInput: true,
			    altFormat: 'F j, Y',
			    dateFormat: 'Y-m-d',
			    defaultDate: moment().format("YYYY-MM-DD")
			})

			$('#fCompany').select2();

			$('#fFname').on('change', e => {
	            e = $(e.target);
	            fFname = e.val();
	        });

			$('#fLname').on('change', e => {
	            e = $(e.target);
	            fLname = e.val();
	        });

			$('#fCompany').on('change', e => {
	            e = $(e.target);
	            fCompany = e.val();
	        });

			$('#fDate').on('change', e => {
	            e = $(e.target);
	            fDate = e.val();
	        });
		});

		function getFilters(){
            return {
                fFname: fFname,
                fLname: fLname,
                fCompany: fCompany,
                fType: fType,
                fDate: fDate,
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
	        							(${patient.company_name})
	        						</td>
	        				`;

	        				let list = "";
	        				let questions = Object.groupBy(package.questions, ({ category_id }) => category_id);

	        				if(questions[null]){
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
	        				}
	        				else{
	        					list = "No Inclusions";
	        				}

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
		                    			type: "PEE",
		                    			_token: $('meta[name="csrf-token"]').attr('content')
		                    		},
		                    		success: result => {
				                    	setTimeout(() => {
		                    				swal.showLoading();
				                    	}, 100);

				                    	setTimeout(() => {
			                    			ss("Success");
					                    	setTimeout(() => {
					                    		requestList(id);
					                    	}, 1000);
				                    	}, 2000);
		                    		}
		                    	})
		                    }
		                    else{
		                    	se('No Package Selected');
		                    }
		        		}
		        	});
        		}
        	})
        }

        function requestList(id){
        	$.ajax({
        		url: '{{ route('patientPackage.get') }}',
        		data: {
    				select: "*",
    				where: ["user_id", id],
    				where2: ["package_id", ">", 2],
    				load: ["package"]
        		},
        		success: result => {
        			result = JSON.parse(result);

        			let packageString = "";

        			if(result.length){
        				result.forEach(pPackage => {
	        				packageString += `
	        					<tr>
	        						<td>${pPackage.package.name}</td>
	        						<td>${pPackage.type == "PEE" ? "PPE" : pPackage.type}</td>
	        						<td>${toDateTime(pPackage.created_at)}</td>
	        						<td>
	        							<a class="btn btn-success" data-toggle="tooltip" title="Add Results" onclick="addResult(${pPackage.id})">
	        								<i class="fas fa-file-prescription"></i>
	        							</a>
	        							<a class="btn btn-info" data-toggle="tooltip" title="Export Invoice" onclick="invoice(${pPackage.id})">
	        								<i class="fas fa-file-pdf"></i>
	        							</a>
	        							<a class="btn btn-warning" data-toggle="tooltip" title="Export to PDF" onclick="pdfExport(${pPackage.id}, ${pPackage.remarks != null ? true : false}, ${id})">
	        								<i class="fas fa-file-pdf"></i>
	        							</a>
	        							<a class="btn btn-danger" data-toggle="tooltip" title="Delete" onclick="deletepPackage(${pPackage.id})">
	        								<i class="fas fa-times"></i>
	        							</a>
	        						</td>
	        					</tr>
	        				`;
        				});
        			}
        			else{
        				packageString = `
	        				<tr>
	        					<td colspan="4" style="text-align: center;">No Package Requested</td>
	        				</tr>
	        			`;
        			}

		        	Swal.fire({
		        		title: "Package Request List",
		        		html: `
                			<table class="table table-hover table-bordered">
                				<thead>
                					<tr>
                						<th>Package Name</th>
                						<th>Type</th>
                						<th>Date</th>
                						<th style="width: 200px;">Result</th>
                					</tr>
                				</thead>
                				<tbody>
                					${packageString}
                				</tbody>
                			</table>
		        		`,
						width: '800px',
						confirmButtonText: 'OK',
		        	})
        		}
        	})
        }

        function deletepPackage(id){
        	sc("Confirmation", "Are you sure you want to delete?", result => {
        		if(result.value){
        			swal.showLoading();
        			update({
        				url: "{{ route('patientPackage.delete') }}",
        				data: {id: id},
        				message: "Success"
        			}, () => {
        				reload();
        			})
        		}
        	});
        }

        function addResult(ppid){
        	let string = "";

        	$.ajax({
        		url: '{{ route('patientPackage.get') }}',
        		data: {
        			select: '*',
        			where: ['id', ppid],
        			load: ["package.questions"],
        			mhr: true,
        		},
        		success: result => {
        			mhr = JSON.parse(result)["mhr"];
        			mhrQuestions = JSON.parse(result)["questions"];
        			result = JSON.parse(result)["package"];

    				let list = "";
    				let questions = Object.groupBy(result.package.questions, ({ category_id }) => category_id);

    				if(questions[null]){
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

	    				// FILES
	    				let attachment = "<span style='color: red;'>No Attached File</span>";

	    				let subjective = generateSubjective(mhrQuestions);

	    				let disabled = "{{ in_array(auth()->user()->role, ['Doctor']) ? "" : "disabled" }}";

			        	Swal.fire({
			        		// title: "Result/Impressions",
			        		html: `
			        			<div class="row">
			        				<div class="col-md-2" style="text-align: left;">
			        					<h2><u><b>Inclusions</b></u></h2>
			        					<br>
			        					${list}
			        					<br>
			        					<br>
			        					<h2><u><b>Attachment</b></u></h2>
			        					${attachment}
			        					<br>
			        					<br>
			        					@if(auth()->user()->role != "Admin" && auth()->user()->role != "Receptionist")
			        						<label for="files" class="btn btn-info">Upload File</label>
			        					<br>
			        					@endif
			        					<input id="files" class="d-none" type="file">
			        				</div>
			        				<div class="col-md-10">

			        					<ul class="nav nav-pills ml-auto" style="padding-left: revert;">
			        					    <li class="nav-item">
			        					        <a class="nav-link active" href="#tab1" data-toggle="tab">
			        					            Medical Evaluation
			        					        </a>
			        					    </li>
			        					    &nbsp;
			        					    <li class="nav-item">
			        					        <a class="nav-link" href="#tab2" data-toggle="tab">
			        					            Result/Impressions
			        					        </a>
			        					    </li>
			        					    &nbsp;
			        					    <li class="nav-item">
			        					        <a class="nav-link" href="#tab3" data-toggle="tab">
			        					            Clinical Assessment
			        					        </a>
			        					    </li>
			        					    &nbsp;
			        					    <li class="nav-item">
			        					        <a class="nav-link" href="#tab4" data-toggle="tab">
			        					            Recommendation
			        					        </a>
			        					    </li>
			        					    &nbsp;
			        					    <li class="nav-item">
			        					        <a class="nav-link" href="#tab5" data-toggle="tab">
			        					            Classification
			        					        </a>
			        					    </li>
			        					    &nbsp;
			        					    <li class="nav-item" style="position: absolute; right: 15px;">
			        					    	<a class="btn btn-success btn-sm" data-toggle="tooltip" title="Save" onclick="saveShortcut()">
			        					    	    <i class="fas fa-save"></i>
			        					    	</a>
			        					    </li>
			        					</ul>

			        					<br>

			        					<div class="tab-content p-0">
			        					    <div class="chart tab-pane active" id="tab1" style="position: relative;">
			        					    	${subjective}
			        					    </div>
			        					    <div class="chart tab-pane" id="tab2" style="position: relative;">
			        					    	<div id="summernote1">${result.remarks ?? ""}</div>
			        					    </div>

			        					    <div class="chart tab-pane" id="tab3" style="position: relative;">
			        					    	<div id="summernote2">${result.clinical_assessment ?? ""}</div>
			        					    </div>

			        					    <div class="chart tab-pane" id="tab4" style="position: relative;">
			        					    	<div id="summernote3">${result.recommendation ?? ""}</div>
			        					    </div>

			        					    <div class="chart tab-pane" id="tab5" style="position: relative; text-align: left; border-left: 1px solid rgb(1 1 1 / 30%); padding-left: 10px;">
		                                        <input type="radio" ${disabled} name="classification" value="Fit to work"> Fit to work
		                                        <br>
		                                        <input type="radio" ${disabled} name="classification" value="Physically fit with minor illness"> hysically fit with minor illness
		                                        <br>
		                                        <input type="radio" ${disabled} name="classification" value="Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)"> Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)
		                                        <br>
		                                        <input type="radio" ${disabled} name="classification" value="Unfit to work"> Unfit to work
		                                        <br>
		                                        <input type="radio" ${disabled} name="classification" value="Pending"> Pending
		                                        <br>
		                                        <br>
		                                        <label htmlFor="c_remarks">Remarks</label>
		                                        <input type="text" id="c_remarks" class="form-control" value="${result.c_remarks ?? ""}" ${disabled}>
			        					    </div>
			        					</div>
			        				</div>
			        			</div>
			        		`,
						  	showClass: {
						    	backdrop: 'swal2-noanimation', // disable backdrop animation
						    	popup: '',                     // disable popup animation
						    	icon: ''                       // disable icon animation
						  	},
			        		position: "top",
			                width: 1500,
			                confirmButtonText: "Save",
							showCancelButton: true,
							cancelButtonColor: errorColor,
							cancelButtonText: 'Cancel',
							allowOutsideClick: false,
							allowEscapeKey: false,
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

								update({
								    url: "{{ route("patientPackage.update") }}",
								    data: {
								        id: mhr.id,
								        question_with_answers: JSON.stringify(array)
								    }
								});
							},
			        		didOpen: () => {
								$('#summernote1, #summernote2, #summernote3').summernote({
									height: 600,
			                		focus: true
								});

								@if(auth()->user()->role == "Receptionist")
									$('.note-editable').css('background-color','#FFFFFF');
									$('.note-editable').attr('contenteditable', false);
								@endif

								$('.note-editable').css('text-align', 'left');
								$('.note-insert').css('display', 'none');

								$(`[name="classification"][value="${result.classification}"]`).prop('disabled', false);
								$(`[name="classification"][value="${result.classification}"]`).click();

								$('#files').on('change', e => {
								    updateFile(ppid);
								});

								let qwa = mhr.question_with_answers;

								if(qwa){
								    qwa = JSON.parse(qwa);

								    qwa.forEach(qwa => {
								        let type = $(`.answer[data-id="${qwa.id}"]`).data('type');

								        if(type == "Dichotomous"){
								            $(`[name="rb${qwa.id}"][value="${qwa.answer}"]`).prop('disabled', false);
								            $(`[name="rb${qwa.id}"][value="${qwa.answer}"]`).click();
								        }
								        else if(type == "Text"){
								            $(`.answer input[data-id="${qwa.id}"]`).val(qwa.answer);
								        }

								        $(`.remark[data-id="${qwa.id}"]`).val(qwa.remark);
								    });
								}
			        		}
			        	}).then(result => {
			        		if(result.value){
			        			swal.showLoading();
			        			update({
			        				url: "{{ route('patientPackage.update') }}",
			        				data: {
			        					id: ppid,
			        					remarks: $('#summernote1').summernote('code'),
			        					clinical_assessment: $('#summernote2').summernote('code'),
			        					recommendation: $('#summernote3').summernote('code'),
			        					classification: $('[name="classification"]:checked').val(),
			        					c_remarks: $('#c_remarks').val(),
			        					doctor_id: {{ auth()->user()->id }}
			        				},
			        				message: "Successfully saved"
			        			});
			        		}
			        	});
			        }
			        else{
			        	se("Package has no inclusions yet.");
			        }
        		}
        	})
        }

        function saveShortcut(){
        	$('.swal2-confirm').click();
        }

        function generateSubjective(questions){
            let keys = Object.keys(questions);
            let categories = questions[keys[keys.length-1]];

            let string = "";

            for (let [k, v] of Object.entries(questions[""])) {
            	let hide = "";
				if(!["Obstetrical History", "Vital Signs", "Anthropometrics", "Visual Acuity", "Systematic Examination", "Medical Evaluation"].includes(v.name)){
					hide = "d-none";
				}

                string += `
                    <div class="row ${hide}">
                        <div class="col-md-12" style="text-align: left;">
                            <b style="font-size: 1.5rem;">${v.name}</b>
                        </div>
                    </div>

                    <table class="table table-hover qtd ${hide}" style="width: 100%; margin-top: 5px; text-align: left;">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Name</th>
                                <th style="width: 30%;" class="answer">Answer</th>
                                <th style="width: 30%;" class="remark">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let temp = questions[v.id];
	    		let disabled = "{{ in_array(auth()->user()->role, ['Doctor']) ? "" : "disabled" }}"; //DISABLE EDITING FOR NON ADMIN/DOCTOR

	    		//ENABLE EDITING FOR THIS 3 CATEGORIES FOR NURSES
	    		@if(auth()->user()->role == "Nurse")
		    		if(["Vital Signs", "Anthropometrics", "Visual Acuity"].includes(v.name)){
		    			disabled = "";
		    		}
	    		@endif

                if(temp){
                    for(let i = 0; i < temp.length; i++){
                        let answer = "";

                        if(temp[i].type == "Text"){
                            answer = `
                                <input type="text" class="form-control" data-id="${temp[i].id}" ${disabled}>
                            `;
                        }
                        else if(temp[i].type == "Dichotomous"){
                            answer = `
                                <input type="radio" name="rb${temp[i].id}" value="1" ${disabled}>Yes
                                &nbsp;
                                <input type="radio" name="rb${temp[i].id}" value="0" ${disabled}>No
                            `;
                        }

                        string += `
                            <tr>
                                <td>${temp[i].name}</td>
                                <td class="answer" data-type="${temp[i].type}" data-id="${temp[i].id}">${answer}</td>
                                <td>
                                    <input type="text" class="form-control remark" data-id="${temp[i].id}" ${disabled}>
                                </td>
                            </tr>
                        `;
                    }
                }

                string += "</tbody></table>";
                historyString = string;
            }

            return historyString;
        }

        async function updateFile(ppid){
            let formData = new FormData();

            formData.append('id', ppid);
            formData.append('file', $("#files").prop('files')[0]);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            await fetch('{{ route('patientPackage.update') }}', {
                method: "POST", 
                body: formData
            });

            Swal.showValidationMessage("New File Uploaded");
        }

        function invoice(id){
        	$.ajax({
        		url: "{{ route('setting.checkClinicSettings') }}",
        		success: result => {
        			if(result){
		        		let data = {};
		        		data.id = id;

		            	window.location.href = `{{ route('patientPackage.exportInvoice') }}?` + $.param(data);
        			}
        			else{
        				se("Complete clinic settings first before exporting");
        			}
        		}
        	})
        }

        function pdfExport(id, rLength, uid){
        	$.ajax({
        		url: "{{ route('setting.checkClinicSettings') }}",
        		success: result => {
        			if(result){
        				$.ajax({
        					url: "{{ route('patientPackage.get') }}",
        					data: {
        						select: "*",
        						where: ["user_id", uid],
        						where2: ["package_id", 2]
        					},
        					success: result2 => {
        						result2 = JSON.parse(result2)[0];
        						
					        	if(!rLength){
					        		se("Their are no available Result/Impressions");
					        		setTimeout(() => {
					        			requestList(uid);
					        		}, 800);
					        	}
					        	else if(result2 == undefined || result2['question_with_answers'] == null){
					        		se("No Medical Examination Report Record");
					        		setTimeout(() => {
					        			requestList(uid);
					        		}, 800);
					        	}
					        	else{
					        		let data = {};
					        		data.id = id;
					        		data.type = "RI";

					            	window.location.href = `{{ route('patientPackage.exportDocument') }}?` + $.param(data);
					        	}
        					}
        				})
        			}
        			else{
        				se("Complete clinic settings first before exporting");
        			}
        		}
        	})
        }

        function addPatient(){
        	$.ajax({
        		url: "{{ route('user.get') }}",
        		data: {
        			select: "*",
        			where: ["role", "Patient"],
        			where2: ["type", "!=", "PEE"],
        			load: ['patient']
        		},
        		success: users => {
        			users = JSON.parse(users);
        			let userString = "";

        			users.forEach(user => {
        				userString += `
        					<option value="${user.id}">${user.fname} ${user.lname} - ${user.patient.company_name} (${user.gender ?? "-"})</option>
        				`;
        			});

        			Swal.fire({
        				title: "Select Patient",
        				html: `
        					<select id="ptadd" class="form-control">
        						<option value="">N/A</option>
        						${userString}
        					</select>
        				`,
        				didOpen: () => {
        					$('#ptadd').select2()
        				},
        				preConfirm: () => {
        					let id = $('#ptadd').val();

        					if(id == ""){
        						Swal.showValidationMessage("Select One");
        					}
        					else{
        						update({
        							url: "{{ route('examList.store') }}",
        							data: {
        								user_id: id,
        								type: "PEE"
        							}
        						})
        					}
        				}
        			}).then(result => {
        				if(result.value){
        					ss("Success");
        					reload();
        				}
        			})
        		}
        	})
        }

        function deleteType(id){
        	sc("Confirmation", "Are you sure you want to delete?", result => {
        		if(result.value){
        			swal.showLoading();
        			update({
        				url: "{{ route('examList.update') }}",
        				data: {id: id, deleted_at: dateTimeNow()},
        				message: "Success"
        			}, () => {
        				reload();
        			})
        		}
        	});
        }

        function exportExcel(){
        	let data = {
        		filters: getFilters(),
        		type: "PPE"
        	};

        	Swal.fire({
        		title: "Choose Date Range",
        		html: `
        			${input('from', 'From', data.filters.fDate, 3, 9)}
        			${input('to', 'To', data.filters.fDate, 3, 9)}
        		`,
        		didOpen: () => {
        			$('[name="from"], [name="to"]').flatpickr({
        			    altInput: true,
        			    altFormat: 'F j, Y',
        			    dateFormat: 'Y-m-d',
        			})
        		}
        	}).then(result => {
        		if(result.value){
        			data.filters.from = $('[name="from"]').val();
        			data.filters.to = $('[name="to"]').val();
        			
        			window.location.href = "{{ route('report.exam') }}?" + $.param(data);
        		}
        	});
        }

        function assignedDoctor(id){
        	$.ajax({
        		url: "{{ route("examList.get") }}",
        		data: {
        			select: "*",
        			where: ['id', id],
        			load: ['attending_doctor.doctor']
        		},
        		success: result => {
        			result = JSON.parse(result)[0];

        			let user = result.attending_doctor;
        			let string = "";

        			if(user){
        				string = `
	    					<div class="card">
		                        <div class="card-header row" style="margin: 1px; background-color: rgb(131, 200, 229);">
		                            <div class="col-md-12">
		                                <h3 class="card-title" style="width: 100%; text-align: left;">
		                                    <i class="fas fa-user mr-1"></i>
		                                    Assigned Doctor
		                                </h3>
		                            </div>
		                        </div>

		                        <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
	                    			<div class="col-md-12">
	    	                			<img src="../${user.avatar}" alt="avatar" width="100" height="100">
	                    			</div>

	                    			<br>
	                    			
	                    			<div class="col-md-12 pInfo-left" style="text-align: left;">
	                    				<span class="label">Name</span>
	                    				<br>
	                    				<span class="pInfo">${user.fname ?? "-"} ${user.lname ?? "-"}</span>
	                    			</div>

	                    			<br>

	                    			<div class="col-md-12 pInfo-left" style="text-align: left;">
	                    				<span class="label">Specialization</span>
	                    				<br>
	                    				<span class="pInfo">${user.doctor.specialization ?? "-"}</span>
	                    			</div>
	                    			
	                    			<br>
	                    			
	                    			<div class="col-md-12 pInfo-left" style="text-align: left;">
	                    				<span class="label">Title</span>
	                    				<br>
	                    				<span class="pInfo">${user.doctor.title ?? "-"}</span>
	                    			</div>
		                        </div>
		                    </div>
        				`;
        			}
        			else{
        				string = "<b><h2>No Assigned Doctor</h2></b>";
        			}

        			Swal.fire({
        				html: `
        					${string}
        				`,
        				confirmButtonText: "Assign New",
        				showCancelButton: true,
        				cancelButtonColor: successColor,
        				cancelButtonText: "OK"
        			}).then(result => {
        				if(result.value){
        					assignDoctor(id);
        				}
        			})
        		}
        	})
        }

        function assignDoctor(id){
			$.ajax({
				url: "{{ route('user.get') }}",
				data: {
					where: ['role', 'Doctor'],
					select: "*"
				},
				success: result => {
					result = JSON.parse(result);

					let doctorString = "";

					result.forEach(doctor => {
						doctorString += `
							<option value="${doctor.id}">${doctor.fname} ${doctor.lname}</option>
						`;
					});

					Swal.fire({
						title: "Select Doctor to assign",
						html: `
							<select id="assigned_doctor" class="form-control">
								<option value="">Select One</option>
								${doctorString}
							</select>
						`,
						showCancelButton: true,
						cancelButtonColor: errorColor,
						didOpen: () => {
							$('#assigned_doctor').select2();
						},
						preConfirm: () => {
							if($('#assigned_doctor').val() == ""){
								Swal.showValidationMessage("You have not selected yet");
							}
						}
					}).then(result2 => {
						if(result2.value){
							update({
							    url: "{{ route("examList.update") }}",
							    data: {
							        id: id,
							        doctor_id: $('#assigned_doctor').val()
							    },
							    message: "Successfully updated assigned doctor"
							}, () => {
								setTimeout(() => {
									assignedDoctor(id);
								}, 1500);
							})
						}
					})
				}
			})
        }

        function evaluation(id){
        	window.open(`{{ route('patient.subjective') }}?id=${id}`, '_blank').focus();
        }
	</script>
@endpush