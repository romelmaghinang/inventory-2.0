<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XoStatus extends Model
{
    protected $table = 'xostatus';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $keyType = 'integer';

    protected $fillable = ['id', 'name'];
}
