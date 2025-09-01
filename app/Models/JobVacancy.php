<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class JobVacancy extends Model
{
    use HasFactory, Searchable;

    protected $table = 'job_vacancies';

    protected $fillable = [
        'title',
        'description',
        'location',
        'experience',
        'skills',
        'salary',
        'type',
        'company_id'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'jobId');
    }

    public function searchableAs(): string
    {
        return 'job_vacancies';
    }

    public function toSearchableArray(): array
    {
        $this->loadMissing('company');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'experience' => $this->experience,
            'skills' => $this->skills,
            'salary' => $this->salary,
            'type' => $this->type,
            'company' => $this->company->name ?? null,
        ];
    }
}

