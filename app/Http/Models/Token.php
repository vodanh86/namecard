<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    protected $table = 'tokens';

    protected $hidden = [
    ];

    protected $appends = [];

    protected $guarded = [];
}
