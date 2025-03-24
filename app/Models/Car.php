<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    // กำหนดว่าเราจะอนุญาตให้ทำการเพิ่มข้อมูลในคอลัมน์เหล่านี้ได้
    protected $fillable = ['name', 'brand', 'year'];
}
