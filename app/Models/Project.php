<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = "projects";
    protected $guarded = ["id"];

    function Image() : HasMany {
        return $this->hasMany(Image::class, "project_id");
    }
}
