<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
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
    protected $table = 'job_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'status',
        'aiGeneratedScore',
        'aiGeneratedFeedback',
        'jobId',
        'resumeId',
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
     * Relationship: A job application belongs to a job vacancy.
     */
    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'jobId', 'id');
    }

    /**
     * Relationship: A job application belongs to a resume.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resumeId', 'id');
    }

    /**
     * Relationship: A job application belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
