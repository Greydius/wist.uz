<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentClassroomController extends Controller
{
    public function attachClassroom($id, $classroom_id, Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'comment' => 'nullable|string'
        ]);

        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Ученик не найден'
            ], 400);
        }

        $classroom = Classroom::find($classroom_id);

        if (!$classroom) {
            return response()->json([
                'success' => false,
                'message' => 'Класс не найден'
            ], 400);
        }

        $classroom->students()->attach($row, $validatedData);

        return response()->json([
            'success' => true,
            'data' => $row
        ], 400);
    }

    public function updateAttachedClassroom($id, $classroom_id, Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'comment' => 'nullable|string'
        ]);

        $row = Model::find($id);

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Ученик не найден'
            ], 400);
        }

        $classroom = Classroom::find($classroom_id);

        if (!$classroom) {
            return response()->json([
                'success' => false,
                'message' => 'Класс не найден'
            ], 400);
        }

        $classroom->students()->attach($row, $validatedData);

        return response()->json([
            'success' => true,
            'data' => $row
        ], 400);
    }
}
