<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Fillup Details | HDR</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ asset('fonts/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/temposdusmus-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/icheck-boostrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/overlayScrollbar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">

        <style>
            .main-header, .content-wrapper{
                margin-left: 0px !important;
            }

            #table td, #table th{
                text-align: center;
            }
        </style>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <div class="preloader"></div>

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <img src="{{ asset($logo ?? "images/HDC App.png") }}" alt="LOGO" height="45">
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" role="button" href="{{ url('/') }}">
                            <i class="fa-solid fa-right-from-bracket">
                                Exit
                            </i>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                {{-- <h1 class="m-0">Personal Medical Record</h1> --}}
                            </div>
                            <div class="col-sm-6">
                                <!-- <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                    <li class="breadcrumb-item active">List</li>
                                </ol> -->
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <section class="col-lg-8 offset-lg-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style="text-align: left;">
                                            <b>Patient Name:</b> {{ $user->fname }} {{ $user->mname }} {{ $user->lname }} {{ $user->suffix }}
                                            <br>
                                            <b>Company:</b> {{ $user->patient->company_name }}
                                        </h3>
                                    </div>

                                    <div class="card-body">
                                        <div id="questionnaire"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                </section>
            </div>
        </div>

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-bundle.min.js') }}"></script>
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('js/temposdusmus-bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/overlayScrollbar.min.js') }}"></script>
        <script src="{{ asset('js/adminlte.min.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>

        <script>
            $(document).ready(() => {
                $.ajax({
                    url: "{{ route("patient.getPatientPackage") }}",
                    data: {
                        select: "*",
                        where: ["user_id", {{ $user->id }}],
                        where2: ["package_id", 2],
                        load: ["package"]
                    },
                    success: result => {
                        result = JSON.parse(result)[0];
                        generateString(result);
                    }
                });
            })

            function generateString(result){
                $.ajax({
                    url: "{{ route('patient.getQuestion') }}",
                    data: {
                        select: "*",
                        where: ["package_id", result.package_id],
                        group: ['category_id']
                    },
                    success: questions => {
                        questions = JSON.parse(questions);

                        let keys = Object.keys(questions);
                        let categories = questions[keys[keys.length-1]];

                        let string = "";

                        for (let [k, v] of Object.entries(questions[""])) {
                            let hide = "";
                            if(["Obstetrical History", "Vital Signs", "Anthropometrics", "Visual Acuity", "Systematic Examination", "Medical Evaluation"].includes(v.name)){
                                hide = "d-none";
                            }

                            if(v.name == "Medication History"){
                                string += `
                                    <div class="row ${hide}">
                                        <div class="col-md-12" style="text-align: left;">
                                            <b style="font-size: 1.5rem;">${v.name}</b>
                                        </div>
                                    </div>

                                    <table class="table table-hover qtd ${hide}" style="width: 100%; margin-top: 5px; text-align: left;">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%;">Medication</th>
                                                <th style="width: 30%;">Dosage</th>
                                                <th style="width: 30%;">
                                                    Frequency
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="medications">
                                            <tr>
                                                <td>
                                                    <textarea class="medication-name form-control"></textarea>
                                                </td>
                                                <td>
                                                    <textarea class="medication-dosage form-control"></textarea>
                                                </td>
                                                <td>
                                                    <textarea class="medication-frequency form-control"></textarea>
                                                </td>
                                            </tr>
                                `;
                            }
                            else{
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

                                if(temp){
                                    for(let i = 0; i < temp.length; i++){
                                        let answer = "";

                                        let remark = `
                                            <td>
                                                <input type="text" class="form-control remark" data-id="${temp[i].id}">
                                            </td>
                                        `;

                                        if(temp[i].type == "Text"){
                                            answer = `
                                                <input type="text" class="form-control" data-id="${temp[i].id}">
                                            `;
                                            remark = "";
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
                                                ${remark}
                                            </tr>
                                        `;
                                    }
                                }
                            }


                            string += "</tbody></table>";
                            historyString = string;
                        }

                        historyString += `
                            <div class="float-right">
                                <a class="btn btn-success" data-toggle="tooltip" title="Save" onclick="save(${result.id})">
                                    SAVE
                                </a>
                            </div>
                        `;

                        $('#questionnaire').html(historyString);

                        let qwa = result.question_with_answers;

                        if(qwa){
                            qwa = JSON.parse(qwa);

                            qwa.forEach(qwa => {
                                // IF MEDICATION HISTORY
                                if(qwa.id == 130){
                                    let medication = qwa.answer;

                                    $(`.medication-name`).val(medication.name);
                                    $(`.medication-dosage`).val(medication.dosage);
                                    $(`.medication-frequency`).val(medication.frequency);
                                }
                                else{
                                    let type = $(`.answer[data-id="${qwa.id}"]`).data('type');

                                    if(type == "Dichotomous"){
                                        $(`[name="rb${qwa.id}"][value="${qwa.answer}"]`).click();
                                        $(`.remark[data-id="${qwa.id}"]`).val(qwa.remark);
                                    }
                                    else if(type == "Text"){
                                        $(`.answer input[data-id="${qwa.id}"]`).val(qwa.answer);
                                    }

                                }
                            });
                        }
                    }
                })
            }

            function save(id){ 
                let array = [];

                let answers = $('td.answer');
                let remarks = $('input.remark');

                let len = $('td.answer').length;

                for(let i = 0; i < len; i++){
                    let id = answers[i].dataset.id;
                    let type = answers[i].dataset.type;
                    let answer = "";
                    let remark = "";

                    if(type == "Dichotomous"){
                        answer = $(`[name="rb${id}"]:checked`).val() ?? null;
                        remark = $(`.remark[data-id="${id}"]`).val();
                    }
                    else if(type == "Text"){
                        answer = $(`.answer input[data-id="${id}"]`).val();
                    }

                    array.push({
                        id: id,
                        answer: answer,
                        remark: remark
                    });
                }

                let names = $('.medication-name');
                let dosages = $('.medication-dosage');
                let frequencys = $('.medication-frequency');

                let medhistory = [];

                for(let i = 0; i < names.length; i++){
                    let name = names[i].value;
                    let dosage = dosages[i].value;
                    let frequency = frequencys[i].value;

                    if(name != "" || dosage != "" || frequency != ""){
                        medhistory.push({
                            name: name,
                            dosage: dosage,
                            frequency: frequency
                        });
                    }
                }

                array.push({
                    id: '130',
                    answer: {
                        name: $('.medication-name').val(),
                        dosage: $('.medication-dosage').val(),
                        frequency: $('.medication-frequency').val()
                    },
                    remark: ''
                });

                swal.showLoading();
                update({
                    url: "{{ route("patient.updateSubjective") }}",
                    data: {
                        id: id,
                        question_with_answers: JSON.stringify(array)
                    },
                    message: "Saved"
                });
            }
        </script>
    </body>
</html>