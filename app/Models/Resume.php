<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resumes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'filename',
        'fileUri',
        'contactDetails',
        'summary',
        'skills',
        'experience',
        'education',
        'userId',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'createdAt',
        'updatedAt',
    ];

    /**
     * Relationship: A resume belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    /**
     * Relationship: A resume can have many job applications.
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'resumeId', 'id');
    }
}
