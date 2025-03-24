<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // Method สำหรับแสดงรายการรถ
    public function index()
    {
        $cars = Car::all();  // ดึงข้อมูลรถทั้งหมด
        return view('cars', compact('cars'));  // ส่งข้อมูลรถไปที่ view
        // return response()->json($cars);
    }

    // ฟังก์ชันจัดการการเพิ่มข้อมูล
    public function store(Request $request)
    {
        // Validate ข้อมูล
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        // เพิ่มข้อมูลรถใหม่
        $car = Car::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'year' => $request->year,
        ]);

        
        // ส่งกลับข้อมูลรถใหม่ที่เพิ่มไปในรูปแบบ JSON
        return response()->json($car);
    }

    public function destroy($id)
{
    // หารถที่ต้องการลบ
    $car = Car::findOrFail($id);

    // ลบข้อมูลรถออกจากฐานข้อมูล
    $car->delete();

    // ส่งข้อมูลกลับเพื่อแจ้งว่าได้ลบเรียบร้อยแล้ว
    return response()->json(['message' => 'Car deleted successfully']);
}
}
