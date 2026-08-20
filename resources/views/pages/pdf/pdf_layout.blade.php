<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pdf Print | {{ get_option('company')['name'] ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
</head>
<body>
<div class="table-header">
    <h3>{{ get_option('company')['name'] ?? '' }}</h3>
    <h4>@yield('pdf_title')</h4>
</div>
@yield('pdf_content')
</body>
</html>
