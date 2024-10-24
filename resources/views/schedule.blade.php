@extends('layouts.app')

@section('title', 'Class Schedule')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      
    </div>

    <section class="section mt-4">
      <div class="card">
        <div class="card-body">
        <h5 class="card-title"><span>Last Updated: <b>{{ $formattedLatestDate }}</b></span></h5>
        
        <!-- Schedule Start -->
            <div class="cd-schedule cd-schedule--loading margin-bottom-lg js-cd-schedule" style="width: calc(100% - 1.25em) !important;">
                     <div class="cd-schedule__timeline">
                       <ul>
                        @foreach($timelineData as $time)
                            <li><span>{{ $time }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <div class="cd-schedule__events">
                    <ul>
                        @foreach($organizedScheduleData as $day => $events)
                            <li class="cd-schedule__group">
                                <div class="cd-schedule__top-info"><span>{{ $day }}</span></div>
                                <ul>
                                    @foreach($events as $event)
                                        <li class="cd-schedule__event">
                                            <a 
                                                data-start="{{ $event['start_military_time'] }}" 
                                                data-end="{{ $event['end_military_time'] }}" 
                                                data-start-civilian="{{ $event['start_civilian_time'] }}" 
                                                data-end-civilian="{{ $event['end_civilian_time'] }}" 
                                                data-content="{{ $event['subject_id'] }}" 
                                                data-event="{{ $event['event_id'] }}" 
                                                href="{{ 'javascript:void()' }}">
                                                <em class="cd-schedule__time">{{ $event['corrected_time'] }}</em>
                                                <em class="cd-schedule__name">{{ $event['subject_code'] }}</em>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Modal Open -->
                    <div class="cd-schedule-modal">
                        <header class="cd-schedule-modal__header">
                            <div class="cd-schedule-modal__content">
                            <span class="cd-schedule-modal__date"></span>
                            <h3 class="cd-schedule-modal__name"></h3>
                            </div>
                    
                            <div class="cd-schedule-modal__header-bg"></div>
                        </header>
                    
                        <div class="cd-schedule-modal__body">
                            <div class="cd-schedule-modal__event-info"></div>
                            <div class="cd-schedule-modal__body-bg"></div>
                            
                            
                        </div>
                    
                        <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
                    </div>
                <!--/ Modal End -->

                <div class="cd-schedule__cover-layer"></div>
            </div>
        <!--/ Schedule Start -->
      </div>

    </section>

  </main><!-- End #main -->

@endsection
