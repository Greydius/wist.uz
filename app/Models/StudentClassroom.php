<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentClassroom extends Pivot
{
    protected $table = 'student_classrooms';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'comment'
    ];

    /**
     * Get the payments for the student classroom.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_classroom_id');
    }

    /**
     * Get the student for the student classroom.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the classroom for the student classroom.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
