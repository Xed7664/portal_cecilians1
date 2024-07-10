<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function getEvents(Request $request)
    {
        // Add logic to fetch events from the database or any other source
        // Add logic to fetch events from the database or any other source
        $events = [
            "message" => "Events fetched successfully.",
            "code" => 10000,
            "data" => [
                [
                    "ID" => "1",
                    "Title" => "Final Defense",
                    "Description" => "Test",
                    "StartDate" => "2024-01-19 17:00:00",
                    "EndDate" => "2024-01-20 00:00:00",
                    "Location" => "test",
                    "EventType" => "general"
                ],
                [
                    "ID" => "2",
                    "Title" => "Sinulog",
                    "Description" => "Test",
                    "StartDate" => "2024-01-21 00:00:00",
                    "EndDate" => "2024-01-21 00:00:00",
                    "Location" => "test",
                    "EventType" => "holiday"
                ],
                [
                    "ID" => "3",
                    "Title" => "Prefinal Exam",
                    "Description" => "test",
                    "StartDate" => "2024-01-08 00:00:00",
                    "EndDate" => "2024-01-13 00:00:00",
                    "Location" => "test",
                    "EventType" => "exam"
                ]
            ]
        ];

        return response()->json($events);
    }
}
