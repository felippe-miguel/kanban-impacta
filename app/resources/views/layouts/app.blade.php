<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kanban Impacta')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <style>
        body {
            background-color: #282a36;
            color: #e0e0e0;
        }
        .bg-default {
            background-color: #212529;
            color: #e0e0e0;
        }

        .bg-default:hover {
            background-color: #2c2f41;
            color: #e0e0e0;
        }
        .table {
            background-color: #21222c;
            color: #e0e0e0;
        }
        .table th,
        .table td {
            background-color: #21222c;
            border-color: #333;
            color: #e0e0e0;
        }
        .modal-header,
        .modal-footer {
            border-color: #333;
        }
        .btn-danger {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        .btn-danger:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: #333;
            border-style: solid;
            border-width: 0;
        }

        .btn-primary {
            --bs-btn-color: #fff !important;
            --bs-btn-bg: #7066e0 !important;
            --bs-btn-border-color: #7066e0 !important;
            --bs-btn-hover-color: #fff !important;
            --bs-btn-hover-bg: #5d55b6 !important;
            --bs-btn-hover-border-color: #5d55b6 !important;
            --bs-btn-focus-shadow-rgb: 49, 132, 253 !important;
            --bs-btn-active-color: #fff !important;
            --bs-btn-active-bg: #7066e0 !important;
            --bs-btn-active-border-color: #7066e0 !important;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important;
            --bs-btn-disabled-color: #fff !important;
            --bs-btn-disabled-bg: #7066e0 !important;
            --bs-btn-disabled-border-color: #7066e0 !important;
        }

        .table td.fit, .table th.fit {
            white-space: nowrap;
            width: 1%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
</body>
</html>
