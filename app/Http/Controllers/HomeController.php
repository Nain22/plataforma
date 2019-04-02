<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //para contar a los estudiantes
        $courses = Course::withCount(['students'])
            //para traer el campos
            ->with('category' , 'teacher' , 'reviews')
            ->where('status' , Course::PUBLISHED)
            //ORDENAR DESENDENTEMENTE
            ->latest()
            ->paginate(12);
        return view('home' , compact('courses'));
    }
}
