<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu yang tersedia dari database
        $menus = Menu::where('is_available', true) // Mengambil menu yang tersedia
                    ->orderBy('name') // Mengurutkan menu berdasarkan nama
                    ->paginate(12);  // Kita menggunakan Menu::paginate(12) untuk mengambil seluruh menu yang ada dengan paginasi

        // Kirim data menu ke view untuk ditampilkan
        return view('customer.menu', compact('menus'));  // Mengirim data menu ke view
    }
}
