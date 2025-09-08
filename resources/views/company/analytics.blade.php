@extends('layouts.app-company')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pie-chart mr-1"></i>
                            Analytics
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="fName">Name</label>
                                <input type="text" id="fName" class="form-control" placeholder="Search name (if blank all)">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="fFrom">From</label>
                                <input type="text" id="fFrom" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="fTo">To</label>
                                <input type="text" id="fTo" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="fType">Type</label>
                                <select  class="form-control" id="fType">
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
                                                <div class="preloader"></div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span style="font-weight: bold;">
                                                            Total Clients/Examinees Seen:
                                                        </span>
                                                        <span class="count">0</span>

                                                        <div class="float-right">
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download" onclick="downloadCharts()">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="type-chart" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <canvas id="gender-chart" width="100%"></canvas>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <canvas id="age-chart" width="100%"></canvas>
                                                    </div>
                                                    <div class="col-md-6">
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

                                            <div class="chart tab-pane" id="classification" style="position: relative;">
                                                <div class="preloader"></div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div style="width: 100%;">
                                                            <canvas id="classification-chart"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" id="patient-list">
                                                        <span></span>
                                                        <br>
                                                        <br>
                                                        <table class="table table-hover table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

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

        #patient-list td{
            padding-top: 5px;
            padding-bottom: 5px;
            font-family: 'Inter', sans-serif;
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
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        var fFrom = moment().subtract(3,'months').format("YYYY-MM-DD");
        var fTo = moment().format("YYYY-MM-DD");
        var fType = "%%";
        var fCompany = "{{ auth()->user()->fname }}";
        var fName = "";

        Chart.register(ChartDataLabels);

        var ctx1, chart1; //TYPE
        var ctx2, chart2; //GENDER
        var ctx3, chart3; //AGE
        var ctx4, chart4; //BMI
        var ctx5, chart5; //DISEASES
        var ctx6, chart6; //CLASSIFICATION

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
            $('#fFrom').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: fFrom
            });

            $('#fTo').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d',
                defaultDate: fTo
            });

            getChart1();

            $('#fFrom, #fTo, #fName, #fType, #fCompany').on('change', e => {
                window[e.target.id] = e.target.value;
                chart1.destroy();
                chart2.destroy();
                chart3.destroy();
                chart4.destroy();
                chart5.destroy();
                chart6.destroy();
                getChart1();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('.tab-content .preloader').css('height', '100%');
                setTimeout(() => {
                    $('.tab-content .preloader').css('height', '0px');
                }, 1000);
            });

            $('#fCompany').select2();
            $('.nav-link').removeClass('active');
            $('.nav-link.analytics').addClass('active');
        });

        function getFilters(){
            return {
                from: fFrom,
                to: fTo,
                name: fName,
                type: fType,
                company: fCompany
            }
        }

        function getChart1(){
            $.ajax({
                url: "{{ route("analytics.getReport1") }}",
                data: {filters: getFilters()},
                success: result => {
                    result = JSON.parse(result);
                    $('#data_analysis .count').html(result.length);

                    let types = [];
                    let genders = [];
                    let ages = [];
                    let bmis = [];
                    let diseases = [];
                    let classifications = [];

                    result.forEach(patient => {
                        types.push(patient.type);
                        genders.push(patient.gender ?? "No Data");
                        ages.push(moment().diff(moment(patient.birthday), 'years'));
                        diseases.push(patient.clinical_assessment);
                        classifications.push(patient.classification ?? "Pending");

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

                    types = types.map(item => item === "PEE" ? "PPE" : item);
                    genders = genders.filter(item => item.toLowerCase() !== "no data");
                    bmis = bmis.filter(item => item.toLowerCase() !== "no data");

                    types = types.reduce((type, item) => {
                      type[item] = (type[item] || 0) + 1;
                      return type;
                    }, {});

                    let order = ["APE", 'PPE', 'ECU'];
                    types = Object.keys(types)
                        .sort((a, b) => order.indexOf(a) - order.indexOf(b))
                        .reduce((acc, key) => {
                        acc[key] = types[key];
                        return acc;
                    }, {});

                    order = ["Male", 'Female'];
                    genders = genders
                        .sort((a, b) => order.indexOf(a) - order.indexOf(b))
                        .reduce((gender, item) => {
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

                    order = ["Underweight", "Normal", "Overweight", "Obese I", "Obese II", "Obese III", "No Data"];
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

                    classifications = classifications.map(s => {
                        if (s.includes("Fit to work")) return "A";
                        if (s.includes("minor")) return "B";
                        if (s.includes("impairments")) return "C";
                        if (s.includes("Unfit")) return "D";
                        return s;
                    });

                    classifications = classifications.reduce((type, item) => {
                      type[item] = (type[item] || 0) + 1;
                      return type;
                    }, {});

                    classifications = Object.keys(classifications)
                        .sort((a, b) => a.localeCompare(b)) // A → Z
                        .reduce((acc, key) => {
                        acc[key] = classifications[key];
                        return acc;
                    }, {});

                    {{-- TYPE CHART --}}
                    ctx1 = document.getElementById('type-chart').getContext('2d');
                    chart1 = new Chart(ctx1, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(types),
                            datasets: [{
                                label: "Types",
                                data: Object.values(types),
                                backgroundColor: getColors(Object.values(types).length, 0.7),
                                borderColor: getColors(Object.values(types).length, 1),
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        font: {
                                            family: 'monospace', // 👈 use monospaced font to align text
                                            size: 16
                                        },
                                        generateLabels: function(chart) {
                                            let dataset = chart.data.datasets[0];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);

                                            return chart.data.labels.map((label, i) => {
                                                let value = dataset.data[i];
                                                let percentage = ((value / total) * 100).toFixed(1) + "%";

                                                return {
                                                    text: `${label}: ${value} (${percentage})`, // 👈 legend shows percent + number
                                                    fillStyle: dataset.backgroundColor[i],
                                                    strokeStyle: dataset.borderColor[i],
                                                    lineWidth: dataset.borderWidth,
                                                    hidden: isNaN(dataset.data[i]) || dataset.data[i] === null,
                                                    index: i
                                                };
                                            });
                                        },
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Exam Type"
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        // 👇 show the label (APE, PPE, ECU) in chart slices
                                        return ctx.chart.data.labels[ctx.dataIndex];
                                    },
                                    color: '#000',
                                    font: { 
                                        size: 16,
                                        family: 'Poppins',
                                        lineHeight: 1.2
                                    },
                                    align: 'start',
                                    anchor: 'center',
                                    offset: (ctx) => {
                                        // get chart radius
                                        let chart = ctx.chart;
                                        let meta = chart.getDatasetMeta(ctx.datasetIndex);
                                        let radius = meta.data[ctx.dataIndex].outerRadius;

                                        // push label outward by ~15% of radius (tweak as needed)
                                        return -(radius * 0.30);
                                    },  
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let dataset = context.dataset;
                                            let value = dataset.data[context.dataIndex];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1) + "%";

                                            // show label, value, and percentage
                                            return `${context.label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            onClick: (e, elements, chart) => {
                                // convert chart to image
                                let img = chart.toBase64Image();
                                // open in new tab
                                let w = window.open();
                                w.document.write(canvasImage(img));
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
                                backgroundColor: getColors(Object.values(genders).length, 0.7),
                                borderColor: getColors(Object.values(genders).length, 1),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        font: {
                                            family: 'monospace', // 👈 use monospaced font to align text
                                            size: 16
                                        },
                                        generateLabels: function(chart) {
                                            let dataset = chart.data.datasets[0];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);

                                            return chart.data.labels.map((label, i) => {
                                                let value = dataset.data[i];
                                                let percentage = ((value / total) * 100).toFixed(1) + "%";

                                                return {
                                                    text: `${label}: ${value} (${percentage})`, // 👈 legend shows percent + number
                                                    fillStyle: dataset.backgroundColor[i],
                                                    strokeStyle: dataset.borderColor[i],
                                                    lineWidth: dataset.borderWidth,
                                                    hidden: isNaN(dataset.data[i]) || dataset.data[i] === null,
                                                    index: i
                                                };
                                            });
                                        },
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Genders"
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        // 👇 show the label (APE, PPE, ECU) in chart slices
                                        return ctx.chart.data.labels[ctx.dataIndex];
                                    },
                                    color: '#000',
                                    font: { 
                                        size: 16,
                                        family: 'Poppins',
                                        lineHeight: 1.2
                                    },
                                    align: 'start',
                                    anchor: 'center',
                                    offset: (ctx) => {
                                        // get chart radius
                                        let chart = ctx.chart;
                                        let meta = chart.getDatasetMeta(ctx.datasetIndex);
                                        let radius = meta.data[ctx.dataIndex].outerRadius;

                                        // push label outward by ~15% of radius (tweak as needed)
                                        return -(radius * 0.30);
                                    },
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let dataset = context.dataset;
                                            let value = dataset.data[context.dataIndex];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1) + "%";

                                            // show label, value, and percentage
                                            return `${context.label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            onClick: (e, elements, chart) => {
                                // convert chart to image
                                let img = chart.toBase64Image();
                                // open in new tab
                                let w = window.open();
                                w.document.write(canvasImage(img));
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
                                backgroundColor: getColors(Object.values(ages).length, 0.7),
                                borderColor: getColors(Object.values(ages).length, 1),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        font: {
                                            family: 'monospace', // 👈 use monospaced font to align text
                                            size: 16
                                        },
                                        generateLabels: function(chart) {
                                            let dataset = chart.data.datasets[0];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);

                                            return chart.data.labels.map((label, i) => {
                                                let value = dataset.data[i];
                                                let percentage = ((value / total) * 100).toFixed(1) + "%";

                                                return {
                                                    text: `${label}: ${value} (${percentage})`, // 👈 legend shows percent + number
                                                    fillStyle: dataset.backgroundColor[i],
                                                    strokeStyle: dataset.borderColor[i],
                                                    lineWidth: dataset.borderWidth,
                                                    hidden: isNaN(dataset.data[i]) || dataset.data[i] === null,
                                                    index: i
                                                };
                                            });
                                        },
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Age Group"
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        // 👇 show the label (APE, PPE, ECU) in chart slices
                                        return ctx.chart.data.labels[ctx.dataIndex];
                                    },
                                    color: '#000',
                                    font: { 
                                        size: 16,
                                        family: 'Poppins',
                                        lineHeight: 1.2
                                    },
                                    align: 'start',
                                    anchor: 'center',
                                    offset: (ctx) => {
                                        // get chart radius
                                        let chart = ctx.chart;
                                        let meta = chart.getDatasetMeta(ctx.datasetIndex);
                                        let radius = meta.data[ctx.dataIndex].outerRadius;

                                        // push label outward by ~15% of radius (tweak as needed)
                                        return -(radius * 0.30);
                                    },
                                    clamp: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let dataset = context.dataset;
                                            let value = dataset.data[context.dataIndex];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1) + "%";

                                            // show label, value, and percentage
                                            return `${context.label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            onClick: (e, elements, chart) => {
                                // convert chart to image
                                let img = chart.toBase64Image();
                                // open in new tab
                                let w = window.open();
                                w.document.write(canvasImage(img));
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
                                backgroundColor: getColors(Object.values(bmis).length, 0.7),
                                borderColor: getColors(Object.values(bmis).length, 1),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        font: {
                                            family: 'monospace', // 👈 use monospaced font to align text
                                            size: 16
                                        },
                                        generateLabels: function(chart) {
                                            let dataset = chart.data.datasets[0];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);

                                            return chart.data.labels.map((label, i) => {
                                                let value = dataset.data[i];
                                                let percentage = ((value / total) * 100).toFixed(1) + "%";

                                                return {
                                                    text: `${label}: ${value} (${percentage})`, // 👈 legend shows percent + number
                                                    fillStyle: dataset.backgroundColor[i],
                                                    strokeStyle: dataset.borderColor[i],
                                                    lineWidth: dataset.borderWidth,
                                                    hidden: isNaN(dataset.data[i]) || dataset.data[i] === null,
                                                    index: i
                                                };
                                            });
                                        },
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Weight Classifications"
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        // 👇 show the label (APE, PPE, ECU) in chart slices
                                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        let percentage = (value / sum * 100).toFixed(1);

                                        let label = ctx.chart.data.labels[ctx.dataIndex];
                                        if(percentage < 5){
                                            if(label == "Normal") label = "N";
                                            else if(label == "Overweight") label = "OW";
                                            else if(label == "Underweight") label = "UW";
                                            else if(label == "Obese I") label = "OB1";
                                            else if(label == "Obese II") label = "OB2";
                                            else if(label == "Obese III") label = "OB3";
                                        }

                                        return label;
                                    },
                                    color: '#000',
                                    font: { 
                                        size: 16,
                                        family: 'Poppins',
                                        lineHeight: 1.2
                                    },
                                    align: 'start',
                                    anchor: 'center',
                                    offset: (ctx) => {
                                        // get chart radius
                                        let chart = ctx.chart;
                                        let meta = chart.getDatasetMeta(ctx.datasetIndex);
                                        let radius = meta.data[ctx.dataIndex].outerRadius;

                                        // push label outward by ~15% of radius (tweak as needed)
                                        return -(radius * 0.40);
                                    },
                                    clamp: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let dataset = context.dataset;
                                            let value = dataset.data[context.dataIndex];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1) + "%";

                                            // show label, value, and percentage
                                            return `${context.label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            onClick: (e, elements, chart) => {
                                // convert chart to image
                                let img = chart.toBase64Image();
                                // open in new tab
                                let w = window.open();
                                w.document.write(canvasImage(img));
                            }
                        }
                    });

                    {{-- DISEASES CHART --}}
                    ctx5 = document.getElementById('diseases-chart').getContext('2d');

                    let datasets = Object.entries(diseases).map(([disease, count], i) => {
                        let dataArr = Object.keys(diseases).map(l => (l === disease ? count : null));

                        return {
                            label: disease,
                            data: dataArr,
                            backgroundColor: getColors(null, 0.7, i),
                            borderColor: getColors(null, 1, i),
                        };
                    });

                    chart5 = new Chart(ctx5, {
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
                                    display: true,
                                    labels: {
                                      font: {
                                        size: 16   // 👈 legend font size
                                      }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Diseases"
                                }
                            },
                            scales: {
                                x: {
                                    stacked: true,
                                    ticks: {
                                      font: {
                                        size: 14   // 👈 x-axis font size
                                      }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    stacked: true,
                                    ticks: {
                                      font: {
                                        size: 14   // 👈 x-axis font size
                                      }
                                    }
                                }
                            },
                            onClick: (e, elements, chart) => {
                                // convert chart to image
                                let img = chart.toBase64Image();
                                // open in new tab
                                let w = window.open();
                                w.document.write(canvasImage(img));
                            }
                        }
                    });

                    {{-- CLASSIFICATION CHART --}}
                    ctx6 = document.getElementById('classification-chart').getContext('2d');
                    chart6 = new Chart(ctx6, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(classifications),
                            datasets: [{
                                data: Object.values(classifications),
                                backgroundColor: getColors(Object.values(classifications).length, 0.7),
                                borderColor: getColors(Object.values(classifications).length, 1),
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        font: {
                                            family: 'monospace', // 👈 use monospaced font to align text
                                            size: 16
                                        },
                                        generateLabels: function(chart) {
                                            let dataset = chart.data.datasets[0];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);

                                            return chart.data.labels.map((label, i) => {
                                                let value = dataset.data[i];
                                                let percentage = ((value / total) * 100).toFixed(1) + "%";

                                                return {
                                                    text: `${label}: ${value} (${percentage})`, // 👈 legend shows percent + number
                                                    fillStyle: dataset.backgroundColor[i],
                                                    strokeStyle: dataset.borderColor[i],
                                                    lineWidth: dataset.borderWidth,
                                                    hidden: isNaN(dataset.data[i]) || dataset.data[i] === null,
                                                    index: i
                                                };
                                            });
                                        },
                                    }
                                },
                                title: {
                                    display: true,
                                    text: "Type Pie Chart"
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        // 👇 show the label (APE, PPE, ECU) in chart slices
                                        return ctx.chart.data.labels[ctx.dataIndex];
                                    },
                                    color: '#000',
                                    font: { 
                                        size: 16,
                                        family: 'Poppins',
                                        lineHeight: 1.2
                                    },
                                    align: 'center',
                                    anchor: 'center'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let dataset = context.dataset;
                                            let value = dataset.data[context.dataIndex];
                                            let total = dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = ((value / total) * 100).toFixed(1) + "%";

                                            // show label, value, and percentage
                                            return `${context.label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            onClick: (evt, elements) => {
                                let key = Object.keys(classifications)[elements[0].index];
                                let list = {
                                    A: "Fit to work",
                                    B: "Physically fit with minor illness",
                                    C: "Employable but with certain impairments or conditions requiring follow-up treatment (employment is at employer's discretion)",
                                    D: "Unfit to work",
                                    Pending: "Pending"
                                };

                                $('#patient-list').append('<div class="preloader"></div>');
                                {{-- DESTROY DATATABLE FIRST IF IT EXISTS --}}
                                if ( $.fn.DataTable.isDataTable('#patient-list table') ) {
                                    $('#patient-list table').DataTable().clear().destroy();
                                }

                                $('#patient-list span').html(key + " - " + list[key] + ' list');
                                getPatientList(list[key]);
                            }
                        }
                    });
                }
            });
        }


        var details = [];
        function getPatientList(classification){
            let  filters = getFilters();
            filters["classification"] = classification;
            details = [];

            $.ajax({
                url: "{{ route("analytics.getReport1") }}",
                data: {filters: filters},
                success: result => {
                    result = JSON.parse(result);
                    
                    let patientString = "";

                    result.forEach(patient => {
                        details[patient.id] = [];
                        details[patient.id].clinical_assessment = cleanString(patient.clinical_assessment);
                        details[patient.id].recommendation = cleanString(patient.recommendation);
                        details[patient.id].c_remarks = cleanString(patient.c_remarks);

                        patientString += `
                            <tr>
                                <td>${patient.user.lname}, ${patient.user.fname}</td>
                                <td style="text-align: center; width: 50px;">
                                    <a class='btn btn-success btn-xs' data-toggle='tooltip' title='View' onClick='showDetails(${patient.id})'>
                                        <i class='fas fa-search fa-sm'></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });

                    $('#patient-list tbody').html(patientString);

                    new DataTable('#patient-list table', {
                        pageLength: 15,
                        lengthMenu: [[10, 15], [10, 15]],
                        pagingType: "simple_numbers",
                        language: {
                            paginate: {
                                previous: "<<",
                                next: ">>"
                            }
                        },
                        columnDefs: [
                            { width: "50px", targets: 1 }
                        ]
                    });

                    $.fn.DataTable.ext.pager.numbers_length = 5;
                    $('#patient-list .preloader').remove();
                }
            });
        }

        function showDetails(id){
            Swal.fire({
                title: "Exam Details",
                html: `
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="container">
                                <div class="row mb-2">
                                    <div class="col-3 text-primary mb-1">Assessment:</div>
                                    <div class="col-9 mb-0 text-muted" style="text-align: left;">${details[id].clinical_assessment}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 text-primary mb-1">Recommendation:</div>
                                    <div class="col-9 mb-0 text-muted" style="text-align: left;">${details[id].recommendation}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 text-primary mb-1">Remarks:</div>
                                    <div class="col-9 mb-0 text-muted" style="text-align: left;">${details[id].c_remarks}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                width: "800px"
            })
        }

        function cleanString(str){
            if (!str) return "-"; // handles null, undefined, empty
            return str
                .replace(/<p>/gi, '')          // remove <p>
                .replace(/<\/p>/gi, '<br>')    // </p> → <br>
                .replace(/<br>$/, ''); 
        }

        function getColors(n, alpha, exact = null) {
            let colors = [
                `rgba(255, 209, 169, ${alpha})`, // pastel orange (required)
                `rgba(167, 199, 231, ${alpha})`, // pastel blue (required)
                `rgba(255, 249, 177, ${alpha})`, // pastel yellow (required)

                // warm tones
                `rgba(186, 225, 255, ${alpha})`, // pastel sky blue
                `rgba(204, 255, 204, ${alpha})`, // pastel lime green
                `rgba(255, 182, 193, ${alpha})`, // pastel pink
                `rgba(204, 255, 229, ${alpha})`, // pastel teal
                `rgba(202, 255, 191, ${alpha})`, // pastel green
                `rgba(255, 204, 229, ${alpha})`, // pastel rose
                `rgba(189, 224, 254, ${alpha})`, // pastel baby blue
                `rgba(207, 226, 243, ${alpha})`, // pastel powder blue
                `rgba(197, 232, 206, ${alpha})`, // pastel mint
                `rgba(255, 218, 185, ${alpha})`, // pastel coral
                `rgba(255, 200, 221, ${alpha})`, // pastel magenta pink
                `rgba(255, 223, 186, ${alpha})`, // pastel peach
                `rgba(255, 179, 186, ${alpha})`, // pastel strawberry
                `rgba(255, 222, 173, ${alpha})`, // pastel navajo

                // cool tones
                `rgba(193, 225, 193, ${alpha})`, // pastel sage green

                // purples
                `rgba(221, 160, 221, ${alpha})`, // pastel purple
                `rgba(216, 191, 216, ${alpha})`, // pastel thistle
                `rgba(221, 220, 235, ${alpha})`, // pastel lavender gray
                `rgba(230, 230, 250, ${alpha})`, // pastel lavender

                // neutrals & fillers
                `rgba(250, 218, 221, ${alpha})`, // pastel blush
                `rgba(240, 230, 140, ${alpha})`, // pastel khaki
                `rgba(255, 228, 196, ${alpha})`, // pastel bisque
                `rgba(253, 253, 150, ${alpha})`  // pastel lemon
            ];


            if(exact != null){
                return colors[exact];
            }

            let temp = [];

            for (let i = 0; i < n; i++) {
                temp.push(colors[i]);
            }
            return temp;
        }

        function canvasImage(img){
            return `
                <html>
                    <head>
                        <title>Chart Preview</title>
                        <style>
                            body {
                                margin: 0;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                                background: #fff;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="${img}" style="height: 90%;">
                    </body>
                </html>
            `;
        }

        function downloadCharts(){
            let tabContent = document.querySelector('.tab-content');

            html2canvas(tabContent, {
                scale: 2,  // higher = sharper image
                useCORS: true
            }).then(canvas => {
                let imgData = canvas.toDataURL("image/png");
                let w = window.open();
                w.document.write(`
                    <html>
                        <head>
                            <title>Tab Content Image</title>
                            <style>
                                body {
                                    margin: 0;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    height: 100vh;
                                    background: #fff;
                                }
                                img {
                                    max-width: 100%;
                                    max-height: 100%;
                                }
                            </style>
                        </head>
                        <body>
                            <img src="${imgData}">
                        </body>
                    </html>
                `);
            });
        }
    </script>
@endpush