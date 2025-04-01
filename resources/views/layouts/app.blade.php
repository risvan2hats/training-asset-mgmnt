<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .badge {
            font-size: 0.875em;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
        .disabled, .no-drop {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            display: flex;
            align-items: center;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
            margin: 0;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: #6c757d;
            margin-left: 0.5rem;
            order: 1;
            border-left: 1px solid #dee2e6;
            padding-left: 0.5rem;
        }
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #dc3545;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: #dee2e6;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        @include('partials.navbar')
        
        <main class="py-4">
            <div class="container">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>
</html>
