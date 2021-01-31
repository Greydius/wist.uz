<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'birthdate',
        'visit_date',
        'application',
        'application_date',
        'assessment',
        'assessment_date',
        'contract',
        'school_start_date',
        'payment'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthdate' => 'datetime:Y-m-d',
        'visit_date' => 'datetime:Y-m-d',
        'application_date' => 'datetime:Y-m-d',
        'assessment_date' => 'datetime:Y-m-d',
        'school_start_date' => 'datetime:Y-m-d',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['actual_classroom', 'school_start_date'];

    /**
     * The classrooms that belong to the student.
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'student_classrooms')->using(StudentClassroom::class)->withTimestamps()->withPivot('id','comment', 'amount')->orderBy('grade', 'desc');
    }

    /**
     * The classrooms that belong to the student.
     */
    public function getActualClassroomAttribute()
    {
        return $this->classrooms()
        // Completed Year
        ->where(function(Builder $query) {
            $query->whereHas('school_year', function(Builder $query) {
                $query->where(function(Builder $query) {
                    $query->where('third_trimester_end_date', '>=', date("Y-m-d"));
                });
            });    
        })->orderBy('grade', 'asc')->first();
    }

    /**
     * The classrooms that belong to the student.
     */
    public function getSchoolStartDateAttribute()
    {
        if($this->actualClassroom) {
            return $this->actualClassroom->year;
        } else {
            return null;
        }
        
    }
}
