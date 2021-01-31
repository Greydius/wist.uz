<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_trimester_start_date',
        'first_trimester_end_date',
        'second_trimester_start_date',
        'second_trimester_end_date',
        'third_trimester_start_date',
        'third_trimester_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'first_trimester_start_date' => 'datetime:Y-m-d',
        'first_trimester_end_date' => 'datetime:Y-m-d',
        'second_trimester_start_date' => 'datetime:Y-m-d',
        'second_trimester_end_date' => 'datetime:Y-m-d',
        'third_trimester_start_date' => 'datetime:Y-m-d',
        'third_trimester_end_date' => 'datetime:Y-m-d',

    ];

    /**
     * Get the classrooms for the school year.
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
