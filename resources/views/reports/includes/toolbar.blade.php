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

    <div class="col-md-2 iInput">
        <select class="form-control" id="fExportType">
            <option value="packagesSold">Packages Sold</option>
            <option value="exportTransactions">Transactions</option>
            <option value="exam" data-type="ape">APE</option>
            <option value="exam" data-type="pee">PPE</option>
            <option value="exam" data-type="ecu">ECU</option>
        </select>
    </div>

    <div class="col-md-4">
        <h3 class="float-right">
            <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Export" onclick="exportReport()">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </h3>
    </div>
</div>
