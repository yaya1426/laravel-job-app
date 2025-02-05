<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'address',
        'industry',
        'website',
        'ownerId',
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
     * Relationship: A company belongs to a user (owner).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerId', 'id');
    }

    /**
     * Relationship: A company has many job vacancies.
     */
    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class, 'companyId', 'id');
    }
}
