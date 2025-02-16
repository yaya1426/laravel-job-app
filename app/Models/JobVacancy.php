<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancy extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
    protected $table = 'job_vacancies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'location',
        'type',
        'salary',
        'companyId',
        'categoryId',
    ];

    protected $dates = ['deleted_at'];

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
     * Relationship: A job vacancy belongs to a company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companyId', 'id');
    }

    /**
     * Relationship: A job vacancy belongs to a job category.
     */
    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'categoryId', 'id');
    }

    /**
     * Relationship: A job vacancy has many job applications.
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'jobId', 'id');
    }
}
