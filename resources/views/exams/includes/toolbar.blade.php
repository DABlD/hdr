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
                <option value="">Filter Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company }}">{{ $company }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1">
            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Search" onclick="reload()">
                <i class="fas fa-search"></i>
            </a>
        </div>

        <div class="col-md-4"></div>

        <div class="col-md-1" style="text-align: right;">
            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Patient" onclick="addPatient()">
                <i class="fas fa-plus"></i> Add Patient
            </a>
        </div>
    </div>
</h3>