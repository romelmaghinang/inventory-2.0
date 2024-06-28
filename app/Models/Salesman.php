<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends Model
{
    use HasFactory;
    protected $table = 'so';
    protected $fillable = ['salesmanId','salesman', 'salesmanInitials'];

    public function getSalesmanIdByName($id)
    {
        return $this->where('salesmanId', $id)->value('salesmanId');
    }

    public function getSalesmanDataByName($name)
    {
        return $this->where('salesman', $name)->first(['salesmanId', 'salesman', 'salesmanInitials']);
    }
}
