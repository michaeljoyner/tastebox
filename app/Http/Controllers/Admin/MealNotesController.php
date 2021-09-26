<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Meals\Meal;
use Illuminate\Http\Request;

class MealNotesController extends Controller
{
    public function store(NoteRequest $request, Meal $meal)
    {
        return $meal->addNote($request->noteTitle(), $request->noteBody(), $request->user()->name);
    }
}
