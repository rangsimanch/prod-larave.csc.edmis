<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/Taiwan_High_Speed_Rail_symbol.svg/1200px-Taiwan_High_Speed_Rail_symbol.svg.png">

</head>

<body class="page-header-fixed">

    <div style="margin-top: 10%;"></div>

    <div class="container-fluid">
        @yield('content')
    </div>

    <div class="scroll-to-top"
         style="display: none;">
        <i class="fa fa-arrow-up"></i>
    </div>

    @include('partials.javascripts')

</body>
</html>