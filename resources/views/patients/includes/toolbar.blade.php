<br>
<br>

<div class="row">
    <div class="col-md-2 iInput">
        <select class="form-control" id="fCompany">
            <option value="">Filter Company</option>
            @foreach($companies as $company)
                <option value="{{ $company }}">{{ $company }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-10">
        <h3 class="float-right">
            <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Admin" onclick="create()">
                <i class="fas fa-plus fa-2xl"></i>
            </a>
        </h3>
    </div>
</div>
