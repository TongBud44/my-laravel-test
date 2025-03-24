<!-- resources/views/cars.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <title>Car List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Car List</h1>
        
        <!-- ฟอร์มสำหรับเพิ่มรถ -->
        <div class="mb-3">
            <input type="text" id="carName" class="form-control" placeholder="Car Name">
        </div>
        <div class="mb-3">
            <input type="text" id="carBrand" class="form-control" placeholder="Car Brand">
        </div>
        <div class="mb-3">
            <input type="number" id="carYear" class="form-control" placeholder="Car Year">
        </div>
        <button id="addCarBtn" class="btn btn-primary">Add Car</button>
        
        <hr>

        <!-- ตารางแสดงข้อมูลรถ -->
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="carTableBody">
                <!-- ข้อมูลรถยนต์จะแสดงที่นี่ -->
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        let carList = [];

        // ฟังก์ชันดึงข้อมูลรถจาก API
        function getCarList() {
            $.ajax({
                url: '/cars',  // URL สำหรับดึงข้อมูล
                type: 'GET',
                success: function(response) {
                    if (Array.isArray(response)) {
                        carList = response;  // เก็บข้อมูลที่ดึงมาใน carList
                        renderTable();  // อัพเดตตาราง
                    }
                },
                error: function(error) {
                    console.error('Error:', error);  // ถ้ามีข้อผิดพลาด
                }
            });
        }

        // ฟังก์ชัน render ตาราง
        function renderTable() {
            let html = '';  // ตัวแปรสำหรับเก็บ HTML ตาราง

            // วนลูปเพื่อสร้างแถวในตาราง
            carList.forEach((car) => {
                html += `
                    <tr>
                        <td>${car.name}</td>  <!-- ชื่อรถ -->
                        <td>${car.brand}</td> <!-- ยี่ห้อรถ -->
                        <td>${car.year}</td>  <!-- ปีที่ผลิต -->
                        <td>
                            <button class="btn btn-danger btn-sm deleteCarBtn" data-id="${car.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });

            // อัพเดตตารางใน DOM
            $('#carTableBody').html(html);
        }

        // เมื่อกดปุ่ม Add Car
        $('#addCarBtn').click(function() {
            const name = $('#carName').val().trim();
            const brand = $('#carBrand').val().trim();
            const year = $('#carYear').val().trim();

            if (name && brand && year) {
                $.ajax({
                    url: '/cars',  // ส่งข้อมูลไปที่ API
                    type: 'POST',
                    data: {
                        name: name,
                        brand: brand,
                        year: year,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                    },
                    success: function(response) {
                        if (Array.isArray(carList)) {
                            carList.push(response);  // เพิ่มข้อมูลรถใหม่ลงใน carList
                            renderTable();  // อัพเดตตาราง
                        } else {
                            console.error("carList is not an array:", carList);
                        }

                        // ล้างฟอร์มหลังการเพิ่ม
                        $('#carName').val('');
                        $('#carBrand').val('');
                        $('#carYear').val('');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            } else {
                alert('Please fill in all fields.');
            }
        });

        // ฟังก์ชันสำหรับลบข้อมูลรถ
        $(document).on('click', '.deleteCarBtn', function() {
            const carId = $(this).data('id');  // รับ id ของรถที่ต้องการลบ
            const row = $(this).closest('tr');  // หาแถวที่มีปุ่มลบ

            $.ajax({
                url: `/cars/${carId}`,  // ส่งคำขอไปยัง URL ลบ
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')  // CSRF Token
                },
                success: function(response) {
                    // ลบแถวจากตาราง
                    row.remove();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        // เรียกฟังก์ชัน getCarList เมื่อหน้าโหลดเสร็จ
        getCarList();  // เรียกฟังก์ชันเมื่อหน้าโหลดเสร็จ
    });
</script>

</body>
</html>
