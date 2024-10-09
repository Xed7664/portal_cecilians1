<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function show($id)
    {
        //$event = Event::findOrFail($id);

        //return view('events.show', compact('event'));
        return view('events.show');
    }
}
