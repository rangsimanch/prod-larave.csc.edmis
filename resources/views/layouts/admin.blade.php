<!DOCTYPE html>
<html>

<style type="text/css">

.pic
{
     margin-top:50px; 
     width:120px;
     margin-left:50px;
     margin-bottom:-60px;
}

.panel
{
    /* background-image:url("http://autoimagesize.com/wp-content/uploads/2014/01/rainbow-aurora-background-wallpaper-colour-images-rainbow-background.jpg");  */
}

.name
{
    position:absolute;
    padding-left:200px;
    font-size:30px;
}

.dropdown
{
    position:absolute;
}

.change
{
 position:relative; 
 bottom:20px;
 padding:1px;
 color:white;
 text-decoration:none;
}


.change:hover
{
 text-decoration:none;
 background-color:black;
 color:white;
}

.loader-wrapper {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  background-color: #DD4B39;
  display:flex;
  justify-content: center;
  align-items: center;
}

.loader {
  display: inline-block;
  width: 30px;
  height: 30px;
  position: relative;
  border: 4px solid #Fff;
  animation: loader 2s infinite ease;
}

.loader-inner {
  vertical-align: top;
  display: inline-block;
  width: 100%;
  background-color: #fff;
  animation: loader-inner 2s infinite ease-in;
}
@keyframes loader {
  0% { transform: rotate(0deg);}
  25% { transform: rotate(180deg);}
  50% { transform: rotate(180deg);}
  75% { transform: rotate(360deg);}
  100% { transform: rotate(360deg);}
}
@keyframes loader-inner {
  0% { height: 0%;}
  25% { height: 0%;}
  50% { height: 100%;}
  75% { height: 100%;}
  100% { height: 0%;}
}
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.6.0/jszip-2.5.0/dt-1.11.4/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.1/sp-1.4.0/sl-1.3.4/sr-1.1.0/datatables.min.css"/>
    
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/Taiwan_High_Speed_Rail_symbol.svg/1200px-Taiwan_High_Speed_Rail_symbol.svg.png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" rel="stylesheet"/>

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet"> 

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css"/>

    
   
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    {{-- <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap.min.css" rel="stylesheet" /> --}}

    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https:////cdn.datatables.net/plug-ins/1.11.4/features/pageResize/dataTables.pageResize.min.js" rel="stylesheet" />
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/all.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
    <link href="{{ asset('css/bootstrap/bootstrap.css') }}" rel="stylesheet" />

    <link href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css" rel="stylesheet">
  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.dataTables.css">

    @yield('styles')
</head>

<body class="sidebar-mini skin-red" style="height: auto; min-height: 100%;">
    <div class="wrapper" style="height: auto; min-height: 100%;">
        <header class="main-header">
            <a href="#" class="logo">
                <span class="logo-mini"><b>EDMIS</b></span>
                <span class="logo-lg">EDMIS</span>
            </a>

            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('global.toggleNavigation') }}</span>
                </a>

                 <!-- Collect the nav links, forms, and other content for toggling -->
                

                 <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
          <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Hi.. {{ \Auth::user()->name }} <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li align="center" class="well">
                {{-- @if(Auth::user()->img_user != '')
                <div align="center"><img class="img-responsive" style="padding:2%; width:90px; height:90px;" src="{{ \Auth::user()->img_user->getUrl('thumb') }}"/></div>
                @else
                <div align="center"><img class="img-responsive" style="padding:2%; width:90px; height:90px;" src="https://static.asianetnews.com/img/default-user-avatar.png"/></div>
                @endif --}}
                <a href="{{ route('admin.users.edit', \Auth::id()) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-co"></span> Settings</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
            </li>
           </ul>
        </li>
      </ul>
    </div>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                @php($alertsCount = \Auth::user()->userUserAlerts()->where('read', false)->count())
                                    @if($alertsCount > 0)
                                        <span class="label label-warning">
                                            {{ $alertsCount }}
                                        </span>
                                    @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="slimScrollDiv" style="position: relative;">
                                        <ul class="menu">
                                            @if(count($alerts = \Auth::user()->userUserAlerts()->withPivot('read')->limit(10)->orderBy('created_at', 'ASC')->get()->reverse()) > 0)
                                                @foreach($alerts as $alert)
                                                    <li>
                                                        <a href="{{ $alert->alert_link ? $alert->alert_link : "#" }}" target="_blank" rel="noopener noreferrer">
                                                            @if($alert->pivot->read === 0) <strong> @endif
                                                                {{ $alert->alert_text }}
                                                                @if($alert->pivot->read === 0) </strong> @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li style="text-align:center;">
                                                    {{ trans('global.no_alerts') }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>


                 @if(count(config('panel.available_languages', [])) > 1)
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <ul class="menu">
                                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                                <li>
                                                    <a href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                @endif
            

            <div class="navbar-custom-menu">
                <ul class="nav navbar-center">
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    @can('construction_contract_select')
                                            {!! Form::open(['method' => 'POST', 'url' => route('admin.construction_contracts-select.select'), 'id' => 'navbar__select-construction_contract-form']) !!}
                                            {!! Form::hidden('redirect', 'back') !!}
                                            {!! Form::select('construction_contract_id', Auth::user()->construction_contracts->pluck('code', 'id'), session('construction_contract_id'), ['class' => 'select3', 'id' => 'navbar__select-construction_contract']) !!}
                                            {!! Form::close() !!}
                                    @endif
                                </a>
                            </li>
                </ul>
            </div>
            
            </nav>
        </header>

        @include('partials.menu')

        <div class="content-wrapper" style="min-height: 960px;">
            @if(session('message'))
                <div class="row" style='padding:20px 20px 0 20px;'>
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="row" style='padding:20px 20px 0 20px;'>
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
        <footer class="main-footer text-center">
            <strong>{{ trans('panel.site_title') }} &copy;</strong> {{ trans('global.allRightsReserved') }}
        </footer>

        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>

    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


    <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.22/pagination/input.js"></script>
    <script src ="https://cdn.datatables.net/plug-ins/1.10.21/pagination/select.js"></script>
    <script src ="https://cdn.datatables.net/plug-ins/1.10.22/pagination/scrolling.js"></script>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    
    {{-- DataTable Add-on --}}
        {{-- Fixed Header --}}
    <link href="https://cdn.datatables.net/fixedheader/3.2.4/css/fixedHeader.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/fixedheader/3.2.4/js/dataTables.fixedHeader.js"></script>
        {{-- Fixed Column --}}
    <link href="https://cdn.datatables.net/fixedcolumns/4.1.0/css/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>




<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



    @include('partials.javascripts')
    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json',
        'th': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Thai.json',
        'zh-Hans': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Chinese.json'
  };

  $('.datatable').scroll(function() {
    if ( $(".fixedHeader-floating").is(":visible") ) {
        $(".fixedHeader-floating").scrollLeft( $(this).scrollLeft() );
    }
});


  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    fixedHeader: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    <script>
        $(document).ready(function () {
    $(".notifications-menu").on('click', function () {
        if (!$(this).hasClass('open')) {
            $('.notifications-menu .label-warning').hide();
            $.get('/admin/user-alerts/read');
        }
    });
});

    </script>
    <script >
        $(document).ready(function() {
    $('.searchable-field').select2({
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("admin.globalSearch") }}',
            dataType: 'json',
            type: 'GET',
            delay: 200,
            data: function (term) {
                return {
                    search: term
                };
            },
            results: function (data) {
                return {
                    data
                };
            }
        },
        escapeMarkup: function (markup) { return markup; },
        templateResult: formatItem,
        templateSelection: formatItemSelection,
        placeholder : '{{ trans('global.search') }}...',
        language: {
            inputTooShort: function(args) {
                var remainingChars = args.minimum - args.input.length;
                var translation = '{{ trans('global.search_input_too_short') }}';

                return translation.replace(':count', remainingChars);
            },
            errorLoading: function() {
                return '{{ trans('global.results_could_not_be_loaded') }}';
            },
            searching: function() {
                return '{{ trans('global.searching') }}';
            },
            noResults: function() {
                return '{{ trans('global.no_results') }}';
            },
        }

    });
    function formatItem (item) {
        if (item.loading) {
            return '{{ trans('global.searching') }}...';
        }
        var markup = "<div class='searchable-link' href='" + item.url + "'>";
        markup += "<div class='searchable-title'>" + item.model + "</div>";
        $.each(item.fields, function(key, field) {
            markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " + item[field] + "</div>";
        });
        markup += "</div>";

        return markup;
    }

    function formatItemSelection (item) {
        if (!item.model) {
            return '{{ trans('global.search') }}...';
        }
        return item.model;
    }
    $(document).delegate('.searchable-link', 'click', function() {
        var url = $(this).attr('href');
        window.location = url;
    });
});

    </script>



    @yield('scripts')
</body>

</html>