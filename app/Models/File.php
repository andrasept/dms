<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'doc_number',
        'doc_name',
        'doc_date',
        'doc_date_exp',
        'doc_note',
        'doc_type',
        'doc_size',
        'dept_id',
        'created_by'
    ];

    protected $dates = ['deleted_at'];

}
