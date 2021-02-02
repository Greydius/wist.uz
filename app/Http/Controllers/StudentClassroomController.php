<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\StudentClassroom;

use PDF;

class StudentClassroomController extends Controller
{
    public function show(Request $request, $id)
    {
        $studentClassroom = StudentClassroom::with(['classroom', 'student', 'payments'])->find($id);

        if (!$studentClassroom) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $studentClassroom
        ]);
    }

    public function invoice(Request $request, $id)
    {
        $studentClassroom = StudentClassroom::with(['classroom.school_year', 'student', 'payments'])->find($id);

        $pdf = PDF::loadView('invoices.student_classroom', [
            'student_classroom' => $studentClassroom,
            'trimesters' => $request->trimesters
        ]);
        return $pdf->download('invoice.pdf');

        return view('invoices.student_classroom', [
            'student_classroom' => $studentClassroom,
            'trimesters' => $request->trimesters
        ]);

        if (!$studentClassroom) {
            return response()->json([
                'success' => false,
                'message' => 'Не найдено'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $studentClassroom
        ]);
    }

    public function addPayment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'trimester' => 'required|string',
            'comment' => 'nullable|string'
        ]);

        $studentClassroom = StudentClassroom::find($id);

        if($studentClassroom->payments()->create($validatedData)) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка добавления'
            ], 500);
        }
    }

    public function updatePayment(Request $request, $id, $payment_id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'trimester' => 'required|string',
            'comment' => 'nullable|string'
        ]);

        $studentClassroom = StudentClassroom::find($id);

        $payment = $studentClassroom->payments()->find($payment_id);

        $updated = $payment->fill($validatedData)->save();

        if($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка обновления'
            ], 500);
        }
    }

    public function deletePayment(Request $request, $id, $payment_id)
    {
        $studentClassroom = StudentClassroom::find($id);

        $payment = $studentClassroom->payments()->find($payment_id);

        if ($payment->delete())
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Ошибка удаления'
            ], 500);
    }
}
