<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $todos = $user->todos()->paginate(10);

        return response()->json(['todos' => $todos]);
    }

    public function show($id)
    {
        $todo = ToDo::findOrFail($id);

        $this->authorize('view', $todo);

        return response()->json(['todo' => $todo]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = Auth::user();

        $todo = $user->todos()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['todo' => $todo, 'message' => 'ToDo created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $todo = ToDo::findOrFail($id);

        $this->authorize('update', $todo);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $todo->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['todo' => $todo, 'message' => 'ToDo updated successfully.']);
    }

    public function destroy($id)
    {
        $todo = ToDo::findOrFail($id);
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->json(['message' => 'ToDo deleted successfully.']);
    }
}
