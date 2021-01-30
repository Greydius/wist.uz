<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYearRequest as Request;

use App\Models\SchoolYear as Model;

class SchoolYearController extends Controller
{

    public function index()
    {
        $rows = Model::orderBy('first_trimester_start_date', 'desc')->get();
 
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
        ], 400);
    }
 
    public function store(Request $request)
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
 
    public function update(Request $request, $id)
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
