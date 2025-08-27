<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link" style="text-align: center;">
        <img src="{{ asset('images/OHN LOGO WBG.jpeg') }}" alt="{{ env('APP_NAME') }}" class="brand-image elevation-3">
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/default_avatar.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" id="profile">
                    {{ auth()->user()->fname }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('company.dashboard') }}">
                        <i class="nav-icon fas fa-users"></i> 
                        <p>Employees</p>
                    </a>
                </li>
            </ul>
        </nav>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link analytics" href="{{ route('company.analytics') }}">
                        <i class="nav-icon fas fa-pie-chart"></i> 
                        <p>Analytics</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

@push('scripts')
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script>

    </script>
@endpush