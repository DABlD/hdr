<br><br>
<h3>
    <div class="row iRow">
        <div class="col-md-2 iInput">
            <input type="text" id="fFname" placeholder="Search First Name" class="form-control">
        </div>

        <div class="col-md-2 iInput">
            <input type="text" id="fLname" placeholder="Search Last Name" class="form-control">
        </div>

        <div class="col-md-2 iInput">
            <select class="form-control" id="fCompany">
                <option value="">All Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company }}">{{ $company }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2"></div>

    </div>

    <div class="row iRow" style="margin-top: 5px;">
        <div class="col-md-2 iInput">
            <input type="text" id="fFrom" placeholder="From" class="form-control">
        </div>
        <div class="col-md-2 iInput">
            <input type="text" id="fTo" placeholder="To" class="form-control">
        </div>

        <div class="col-md-8" style="text-align: right;">
            {{-- <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Search" onclick="reload()">
                <i class="fas fa-search"></i> Search
            </a> --}}
            @if(auth()->user()->role != "Doctor")
                <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Patient" onclick="addPatient()">
                    <i class="fas fa-plus"></i> Add Patient
                </a>
                <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Export" onclick="exportExcel()">
                    <i class="fas fa-file-excel"></i> Export
                </a>
            @endif
        </div>
    </div>
</h3>