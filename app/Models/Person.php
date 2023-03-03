<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person';
    protected $fillable = ['nama', 'jenis_kelamin', 'parent_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(Person::class, 'parent_id', 'id');
    }
}
