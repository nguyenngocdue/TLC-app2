<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Kiểm tra xem form đã được gửi đi hay chưa
            if (isset($_POST["option_print_layout"])) {
                $selectedOption = $_POST["option_print_layout"];
                dd(123);
                
                // $selectedOption chứa giá trị mà người dùng đã chọn trong dropdown
                // Bạn có thể sử dụng $selectedOption cho mục đích xử lý sau này
                echo "Bạn đã chọn: " . $selectedOption;
            }
        }
          
        return view("welcome-due", [
        ]);
    }
}
