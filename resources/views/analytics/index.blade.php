@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box-archive mr-1"></i>
                            Packages Sold
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Search name (if blank all)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="from">From</label>
                                <input type="text" id="from" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="to">To</label>
                                <input type="text" id="to" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="type">Type</label>
                                <select  class="form-control" id="type">
                                    <option value="%%">All</option>
                                    <option value="APE">APE</option>
                                    <option value="PEE">PE</option>
                                    <option value="ECU">ECU</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body custom-tabs">
                                        <ul class="nav nav-pills ml-auto" style="padding-left: revert;" style="text-align: center;">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#data_analysis" data-toggle="tab" data-href="data_analysis">Data Analysis</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                                <a class="nav-link" href="#classification" data-toggle="tab" data-href="classification">Classification</a>
                                            </li>&nbsp;
                                            <li class="nav-item">
                                        </ul>

                                        <br>

                                        <div class="tab-content p-0">
                                            <div class="chart tab-pane active" id="data_analysis" style="position: relative;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span style="font-weight: bold;">
                                                            Total Clients/Examinees Seen:
                                                        </span>
                                                        <span class="count">0</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <canvas id="classification-chart" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <canvas id="gender-chart" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <canvas id="age-chart" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <canvas id="bmi-chart" width="100%"></canvas>
                                                    </div>
                                                </div>

                                                <br>
                                                <hr>
                                                <br>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <canvas id="diseases-chart" width="100%"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chart tab-pane" id="results" style="position: relative;">2</div>
                                            <div class="chart tab-pane" id="classification" style="position: relative;">3</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        table{
            margin-top: 30px;
        }

        .table-container{
            height: 500px;
            width: 100%;
            overflow-y: scroll;
        }

        .custom-tabs .nav {
            display: flex;
            justify-content: flex-start;   /* or center */
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: .75rem;
        }

        .custom-tabs .nav-link {
            border-radius: 999px;
            padding: .45rem .9rem;
            background: #f1f5f9;           /* soft slate */
            color: #475569;                 /* slate-700 */
            font-weight: 600;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .custom-tabs .nav-link:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .custom-tabs .nav-link.active {
            background: var(--tab-accent);
            color: #fff;
            box-shadow: 0 6px 16px rgba(74,192,192,.25);
        }

        .custom-tabs .tab-pane {
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0,0,0,.03);
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/charts.min.js') }}"></script>
    <script src="{{ asset('js/numeral.min.js') }}"></script>

    <script>
        var from = moment().subtract(6,'months').format("YYYY-MM-DD");
        var to = moment().format("YYYY-MM-DD");
        var type = "%%";
        var name = "";

        var ctx1, chart1; //CLASSIFICATION
        var ctx2, chart2; //GENDER
        var ctx3, chart3; //AGE
        var ctx4, chart4; //BMI
        var ctx5, chart5; //DISEASES

        const diseaseMap = {
            "Hypertension": ["HTN", "HPN"],
            "Diabetes Mellitus": ["DM", "T2DM", "NIDDM"],
            "Type 1 Diabetes": ["T1DM", "IDDM"],
            "Dyslipidemia": ["HLD"],
            "Obesity / Overweight": ["OB", "Obese"],
            "Fatty Liver": ["NAFLD", "FLD"],
            "Hyperuricemia": ["Gout"],
            "Heart Disease": ["HD", "CAD", "IHD", "CHD"],
            "Myocardial Infarction": ["MI", "AMI", "Heart Attack"],
            "Congestive Heart Failure": ["CHF", "HF"],
            "Asthma": [],
            "Tuberculosis": ["TB", "PTB"],
            "Chronic Kidney Disease": ["CKD", "CRF", "ESRD"],
            "Hypothyroidism": ["HypoT"],
            "Hyperthyroidism": ["HyperT"],
            "Thyromegaly": ["Goiter"],
            "PCOS": ["Polycystic"],
            "Myopia": ["Nearsighted"],
            "Hyperopia": ["Farsighted"],
            "Astigmatism": [],
            "Anemia": [],
            "Blood Dyscrasia": [],
            "Lupus": ["SLE"],
            "Rheumatoid Arthritis": ["RA"],
            "Scoliosis": ["Dextroscoliosis", "Levoscoliosis"],
            "Allergic Rhinitis": ["AR"],
            "GERD": ["Acid Reflux"],
            "COPD": ["Emphysema", "Chronic Bronchitis"],
            "Stroke": ["CVA", "Infarct"],
            "Hearing Loss": ["HOH"],
            "Psoriasis": ["PsO"],
            "Dermatitis": ["Eczema"],
        };

        $(document).ready(() => {
            $('#from').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: from
            });

            $('#to').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: to
            });

            getChart1();

            $('#from, #to, #name, #type').on('change', e => {
                window[e.target.id] = e.target.value;
                chart1.destroy();
                chart2.destroy();
                chart3.destroy();
                chart4.destroy();
                getChart1();
            });
        });

        function getFilters(){
            return {
                from: from,
                to: to,
                name: name,
                type: type
            }
        }

        function getChart1(){
            $.ajax({
                url: "{{ route("analytics.getReport1") }}",
                data: {filters: getFilters()},
                success: result => {
                    result = JSON.parse(result);
                    $('#data_analysis .count').html(result.length);

                    let classifications = [];
                    let genders = [];
                    let ages = [];
                    let bmis = [];
                    let diseases = [];

                    result.forEach(patient => {
                        classifications.push(patient.classification ?? "Pending");
                        genders.push(patient.gender ?? "No Data");
                        ages.push(moment().diff(moment(patient.birthday), 'years'));
                        diseases.push(patient.clinical_assessment);

                        let qwa = JSON.parse(patient.question_with_answers);
                        if(qwa){
                            let flag = true;
                            for(let answer of qwa){
                                if(answer.id == 173){
                                    if(answer.answer){
                                        bmis.push(answer.answer ?? "No Data");
                                        flag = false;
                                        break;
                                    }
                                }
                            }

                            if(flag){
                                bmis.push("No Data");
                            }
                        }
                        else{
                            bmis.push("No Data");
                        }
                    });

                    classifications = classifications.map(s => {
                        if (s.includes("impairments")) return "Employable but with certain impairments";
                        return s;
                    });

                    classifications = classifications.reduce((classification, item) => {
                      classification[item] = (classification[item] || 0) + 1;
                      return classification;
                    }, {});

                    classifications = Object.keys(classifications)
                        .sort((a, b) => a.localeCompare(b)) // A → Z
                        .reduce((acc, key) => {
                        acc[key] = classifications[key];
                        return acc;
                    }, {});

                    genders = genders.reduce((gender, item) => {
                      gender[item] = (gender[item] || 0) + 1;
                      return gender;
                    }, {});

                    ages = ages.reduce((groups, age) => {
                        let range;

                        if (age < 18) range = "Below 18";
                        else if (age < 29) range = "18-29";
                        else if (age <= 40) range = "30-40";
                        else if (age <= 50) range = "41-50";
                        else if (age <= 60) range = "51-60";
                        else if (age <= 70) range = "61-70";
                        else range = "70+";

                        groups[range] = (groups[range] || 0) + 1;
                        return groups;
                    }, {});

                    ages = Object.keys(ages)
                        .sort((a, b) => {
                        let getStart = str => parseInt(str.match(/\d+/)?.[0] ?? -1, 10);
                        return getStart(a) - getStart(b);
                    })
                        .reduce((acc, key) => {
                        acc[key] = ages[key];
                        return acc;
                    }, {});

                    bmis = bmis.reduce((groups, bmi) => {
                        let range;

                        if (bmi == "No Data") range = "No Data";
                        else if (bmi < 18.5) range = "Underweight";
                        else if (bmi < 24.9) range = "Normal";
                        else if (bmi <= 29.9) range = "Overweight";
                        else if (bmi <= 34.9) range = "Obese I";
                        else if (bmi <= 39.9) range = "Obese II";
                        else range = "Obese III";

                        groups[range] = (groups[range] || 0) + 1;
                        return groups;
                    }, {});

                    const order = ["Underweight", "Normal", "Overweight", "Obese I", "Obese II", "Obese III", "No Data"];
                    bmis = Object.keys(bmis)
                        .sort((a, b) => order.indexOf(a) - order.indexOf(b))
                        .reduce((acc, key) => {
                        acc[key] = bmis[key];
                        return acc;
                    }, {});

                    diseases = diseases.reduce((counts, paragraph) => {
                        if (!paragraph || typeof paragraph !== "string") return counts;

                        const text = paragraph.toLowerCase();

                        for (const [disease, aliasesRaw] of Object.entries(diseaseMap)) {
                            const aliases = Array.isArray(aliasesRaw) ? aliasesRaw : [aliasesRaw];

                            // Create a combined list of terms (disease + aliases)
                            const terms = [disease, ...aliases];

                            for (const term of terms) {
                                const regex = new RegExp(`\\b${term.toLowerCase()}\\b`, "i");
                                if (regex.test(text)) {
                                    counts[disease] = (counts[disease] || 0) + 1;
                                    break; // stop after first match for this disease
                                }
                            }
                        }

                        return counts;
                    }, {});

                    diseases = Object.keys(diseases)
                        .sort((a, b) => a.localeCompare(b)) // alphabetic by disease name
                        .reduce((obj, key) => {
                        obj[key] = diseases[key];
                        return obj;
                    }, {});

                    {{-- CLASSIFICATION CHART --}}
                    ctx1 = document.getElementById('classification-chart').getContext('2d');
                    chart1 = new Chart(ctx1, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(classifications),
                            datasets: [{
                                label: "Classifications",
                                data: Object.values(classifications),
                                backgroundColor: generateRandomColors(Object.values(classifications).length, 0.7),
                                {{-- borderColor: generateRandomColors(Object.values(classifications).length, 1), --}}
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                                plugins: {
                                    legend: {
                                    position: "top",
                                },
                                title: {
                                    display: true,
                                    text: "Classification Pie Chart"
                                }
                            }
                        }
                    });

                    {{-- GENDER CHART --}}
                    ctx2 = document.getElementById('gender-chart').getContext('2d');
                    chart2 = new Chart(ctx2, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(genders),
                            datasets: [{
                                label: "Gender",
                                data: Object.values(genders),
                                backgroundColor: generateRandomColors(Object.values(genders).length, 0.7),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                                plugins: {
                                    legend: {
                                    position: "top",
                                },
                                title: {
                                    display: true,
                                    text: "Gender Pie Chart"
                                }
                            }
                        }
                    });

                    {{-- AGE CHART --}}
                    ctx3 = document.getElementById('age-chart').getContext('2d');
                    chart3 = new Chart(ctx3, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(ages),
                            datasets: [{
                                label: "Age",
                                data: Object.values(ages),
                                backgroundColor: generateRandomColors(Object.values(ages).length, 0.7),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                                plugins: {
                                    legend: {
                                    position: "top",
                                },
                                title: {
                                    display: true,
                                    text: "Age Range Pie Chart"
                                }
                            }
                        }
                    });

                    {{-- BMI CHART --}}
                    ctx4 = document.getElementById('bmi-chart').getContext('2d');
                    chart4 = new Chart(ctx4, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(bmis),
                            datasets: [{
                                label: "BMI",
                                data: Object.values(bmis),
                                backgroundColor: generateRandomColors(Object.values(bmis).length, 0.7),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                                plugins: {
                                    legend: {
                                    position: "top",
                                },
                                title: {
                                    display: true,
                                    text: "BMI Pie Chart"
                                }
                            }
                        }
                    });

                    {{-- DISEASES CHART --}}
                    ctx5 = document.getElementById('diseases-chart').getContext('2d');

                    let datasets = Object.entries(diseases).map(([disease, count], i) => {
                        let dataArr = Object.keys(diseases).map(l => (l === disease ? count : null));
                        let color = generateRandomColors(1, 0.6)[0];

                        return {
                            label: disease,
                            data: dataArr,
                            backgroundColor: color,
                            borderColor: color.replace(/0\.6/, "1"),
                        };
                    });

                    console.log(datasets);

                    myChart5 = new Chart(ctx5, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(diseases),
                            datasets: datasets
                        },
                        options: {
                            barThickness: 50,
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                },
                                title: {
                                    display: true,
                                    text: "Diseases"
                                }
                            },
                            scales: {
                                x: {
                                    stacked: true,
                                },
                                y: {
                                    beginAtZero: true,
                                    stacked: true,
                                }
                            }
                        }
                    });
                }
            });
        }

        function generateRandomColors(n, alpha = 0.7) {
            let colors = [];
            for (let i = 0; i < n; i++) {
                const r = Math.floor(Math.random() * 256);
                const g = Math.floor(Math.random() * 256);
                const b = Math.floor(Math.random() * 256);
                colors.push(`rgba(${r}, ${g}, ${b}, ${alpha})`);
            }
            return colors;
        }
    </script>
@endpush