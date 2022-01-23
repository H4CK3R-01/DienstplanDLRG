@extends('_layouts.application')

@section('head')
    <link href="/plugins/fullcalendar/css/main.css" rel="stylesheet" />
    <script src="/plugins/fullcalendar/js/main.min.js"></script>
    <script src="/plugins/fullcalendar/locales/de.js"></script>
@endsection

@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Kalender
                </h2>
                @if($isAdmin || $isTrainingEditor)
                <ul class="header-dropdown m-r--5">
                    <a href="{{action('CalendarController@create')}}">
                        <button type="button" class="btn btn-success waves-effect">
                            <i class="material-icons">playlist_add</i>
                            <span>Hinzufügen</span>
                        </button>
                    </a>
                </ul>
                @endif
            </div>
            <div class="container">
                <div id='calendar'></div>
            </div>
            <br>
        </div>
    </div>

@endsection

@section('post_body')

    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'de',
                @if(Browser::isDesktop())
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay,listMonth',
                },
                @else
                initialView: 'listMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'listMonth',
                },
                @endif
                weekNumbers: true,
                firstDay: 1,
                //navLinks: true, // can click day/week names to navigate views
                dayMaxEvents: true, // allow "more" link when too many events
                //editable: true,
                eventSources: [
                    {
                        //calendar events
                        events: [
                            @forEach($calendars as $calendar)
                            {
                                id : {{$calendar->id}},
                                title  : '{{$calendar->title}} @if(!empty($calendar->verantwortlicher)) ({{$calendar->verantwortlicher}}) @endif',
                                start  : '{{\Carbon\Carbon::parse($calendar->date)->toIso8601String()}}',
                                @if(!empty($calendar->dateEnd))
                                end: '{{\Carbon\Carbon::parse($calendar->dateEnd)->toIso8601String()}}',
                                @endif
                            },
                            @endforeach
                        ],
                    },
                    {
                        //training events
                        events: [
                            @forEach($trainings as $training)
                            {
                                id : {{$training->id}},
                                title  : '{{$training->title}}',
                                start  : '{{\Carbon\Carbon::parse($training->date)->toIso8601String()}}',
                                @if(!empty($training->dateEnd))
                                end: '{{\Carbon\Carbon::parse($training->dateEnd)->toIso8601String()}}',
                                @endif
                            },
                            @endforeach
                            ],
                        color: '#607D8B',
                    },
                    {
                        //TODO: services of user
                        events: [
                        ],
                        color: '#F44336',
                        textColor: 'black'
                    },
                    {
                        //TODO: all services where user is not paticipating
                    },
                ]

            });
            calendar.render();

        });

    </script>
@endsection