@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.tasksCalendar.title') }}
                </div>
                <div class="panel-body">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />
                    <div id="calendar"></div>

                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                
                header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                        },
                       
                    eventLimit: true,
                events : [
@foreach($events as $event)
@if($event->due_date)
                            {
                                title: '{{ $event->name }}',
                                description: 'TEST DES',
                                start: '{{ \Carbon\Carbon::createFromFormat(config('panel.date_format'),$event->due_date)->format('Y-m-d')}}',
                                end: '{{ \Carbon\Carbon::createFromFormat(config('panel.date_format'),$event->end_date)->format('Y-m-d')}}',

                                // url : '{{ url('admin/tasks').'/'.$event->id.'/edit' }}'
                            },
@endif
@endforeach
                ],
            }),
        });
</script>

@stop