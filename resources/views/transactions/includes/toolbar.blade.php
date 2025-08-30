<br>
<br>

<div class="row">
    <div class="col-md-2 iInput">
        <input type="text" id="fFrom" placeholder="From" class="form-control">
    </div>
    <div class="col-md-2 iInput">
        <input type="text" id="fTo" placeholder="To" class="form-control">
    </div>

    <div class="col-md-2 iInput">
        <select class="form-control" id="fCompany">
            <option value="%%">All Company</option>
            @foreach($companies as $company)
                @if($company != null)
                    <option value="{{ $company }}">{{ $company }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <h3 class="float-right">
            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Patient" onclick="create()">
                <i class="fas fa-plus fa-2xl"></i>
            </a>
            <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Export" onclick="exportCensus()">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </h3>
    </div>
</div>
