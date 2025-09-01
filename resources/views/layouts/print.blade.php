<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Keuangan</title>
    <meta charset="UTF-8">
    <style>
        body { margin: 20px; }
        @media print {
            body { margin: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>