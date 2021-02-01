<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Requests\ClassroomRequest;

use App\Models\Classroom as Model;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $rows = new Model;

        $student_id = $request->student_id;
        if($student_id) {
            $rows = $rows->whereHas('students', function(Builder $query) use($student_id) {
                return $query->where('students.id', $student_id);
            });
        }

        $rows = $rows->orderBy('grade', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    public function show($id)
    {
        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $row
        ]);
    }

    public function students($id)
    {
        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $row->students
        ]);
    }

    public function store(ClassroomRequest $request)
    {
        $validatedData = $request->validated();

        $row = Model::create($validatedData);

        if ($row)
            return response()->json([
                'success' => true,
                'data' => $row
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Не добавлено'
            ], 500);
    }

    public function update(ClassroomRequest $request, $id)
    {
        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        $validatedData = $request->validated();

        $updated = $row->fill($validatedData)->save();

        if ($updated)
            return response()->json([
                'success' => true,
                'data' => $row
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Не возможно обновить'
            ], 500);
    }

    public function destroy($id)
    {
        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        if ($row->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Не возможно удалить'
            ], 500);
        }
    }
}
