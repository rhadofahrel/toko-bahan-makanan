<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $paginated = Product::paginate($search, 10, $page);
        $products = $paginated['data'];
        return view('admin.index', compact('products', 'search'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        
        $photoPath = '';
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $photoPath = $file->storeAs('products', $filename, 'public');
        }
        
        Product::createProduct([
            'name' => $request->name,
            'price' => $request->price,
            'photo' => $photoPath ?: ''
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $photoPath = $file->storeAs('products', $filename, 'public');
        }
        
        $result = Product::updateProduct($id, [
            'name' => $request->name,
            'price' => $request->price,
            'photo' => $photoPath
        ]);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }
    
    public function destroy($id)
    {
        $result = Product::deleteProduct($id);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }
    
    public function getAll(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $products = Product::paginate($search, 10, $page);
        
        return response()->json([
            'success' => true,
            'data' => $products['data'],
            'pagination' => [
                'current_page' => $products['current_page'],
                'last_page' => $products['last_page'],
                'total' => $products['total'],
                'per_page' => $products['per_page']
            ]
        ]);
    }
}