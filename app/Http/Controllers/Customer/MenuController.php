<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('search');
        
        $query = Menu::where('is_available', true);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $menus = $query->orderBy('category')
                      ->orderBy('name')
                      ->paginate(12);
        
        // Get all available categories for filter
        $categories = Menu::where('is_available', true)
                         ->distinct()
                         ->pluck('category')
                         ->sort();
        
        return view('customer.menu', compact('menus', 'categories', 'category', 'search'));
    }
}
