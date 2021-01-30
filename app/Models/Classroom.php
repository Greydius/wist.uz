<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_year_id',
        'grade',
        'symbol',
        'limit'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['students_count', 'year'];

        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'students',
    ];

    /**
     * Get the school year that owns the classroom.
     */
    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * The students that belong to the classroom.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classrooms')->using(StudentClassroom::class)->withTimestamps();
    }

    /**
     * Get the classroom students count.
     *
     * @return string
     */
    public function getStudentsCountAttribute()
    {
        return $this->students->count();
    }

    /**
     * Get the classroom year.
     *
     * @return string
     */
    public function getYearAttribute()
    {
        return $this->school_year->first_trimester_start_date->format('d.m.Y');
    }
}
