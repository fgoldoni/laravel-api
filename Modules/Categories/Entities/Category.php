<?php

namespace Modules\Categories\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Rinvex\Categories\Models\Category as Model;

class Category extends Model
{
    use SoftDeletes;
}
