<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Requests\StudentRequest;

use App\Models\Student as Model;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $rows = new Model;

        $classroom_id = $request->classroom_id;
        if($classroom_id) {
            $rows = $rows->whereHas('classrooms', function(Builder $query) use($classroom_id) {
                return $query->where('classrooms.id', $classroom_id);
            });
        }

        $actual_classrooms_ids = $request->actual_classroom;
        if($actual_classrooms_ids) {
            $rows = $rows->whereHas('classrooms', function(Builder $query) use($actual_classrooms_ids) {
                return $query->where('classrooms.id', $actual_classrooms_ids);
            });
        }

        $filters = [
            'basic' => ['id'],
            'like' => ['name'],
            'variant' => ['application', 'assessment', 'contract', 'payment'],
            'daterange' => ['birthdate', 'visit_date', 'application_date', 'assessment_date'],
        ];

        foreach($filters['basic'] as $filter) {
            if($request->{$filter}) {
                $rows = $rows->where($filter, $request->{$filter});
            }
        }

        foreach($filters['like'] as $filter) {
            if($request->{$filter}) {
                $rows = $rows->where($filter, 'like', '%' . $request->{$filter} .'%');
            }
        }

        foreach($filters['variant'] as $filter) {
            if($request->{$filter}) {
                $rows = $rows->whereIn($filter, $request->{$filter});
            }
        }

        foreach($filters['daterange'] as $filter) {
            if($request->{$filter}) {
                $from = date($request->{$filter}[0]);
                $to = date($request->{$filter}[1]);

                $rows = $rows->whereBetween($filter, [$from, $to]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $rows->get(),
            'request' => $request->all(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $row = new Model;

        if($request->with_classrooms) {
            $row = $row->with(['classrooms']);
        }

        $row = $row->find($id);

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

    public function store(StudentRequest $request)
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

    public function attachClassroom(Request $request, $id, $classroom_id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'comment' => 'nullable|string'
        ]);

        $row = Model::find($id);

        try {
            $row->classrooms()->attach($classroom_id, $validatedData);

            return response()->json([
                'success' => true
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAttachedClassroom(Request $request, $id, $classroom_id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'comment' => 'nullable|string'
        ]);

        $row = Model::find($id);

        if ($row->classrooms()->updateExistingPivot($classroom_id, $validatedData))
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Не добавлено'
            ], 500);
    }

    public function detachClassroom(Request $request, $id, $classroom_id)
    {
        $row = Model::find($id);

        if ($row->classrooms()->detach($classroom_id))
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Не добавлено'
            ], 500);
    }

    public function update(StudentRequest $request, $id)
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
