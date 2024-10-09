<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\{Semester, SchoolYear, Post, User};

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Perform a search on posts where content contains the query
        $postResults = Post::where('content', 'LIKE', '%' . $query . '%')->get();

        // Perform a search on users where Fullname or username contains the query
        $userResults = User::where('username', 'LIKE', '%' . $query . '%')
            ->orWhereHas('student', function ($studentQuery) use ($query) {
                $studentQuery->where('FullName', 'LIKE', '%' . $query . '%');
            })
            ->orWhereHas('employee', function ($employeeQuery) use ($query) {
                $employeeQuery->where('FullName', 'LIKE', '%' . $query . '%');
            })
            ->get();

        // You can pass the search results to a view
        return view('search', compact('postResults', 'userResults', 'query'));
    }
}
