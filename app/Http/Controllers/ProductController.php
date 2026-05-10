<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $paginated = Product::paginate($search, 12, $page);
        $products = $paginated['data'];
        return view('user.index', compact('products', 'search'));
    }
    
    public function getProducts(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $products = Product::paginate($search, 12, $page);
        
        return response()->json([
            'success' => true,
            'data' => $products['data'],
            'pagination' => [
                'current_page' => $products['current_page'],
                'last_page' => $products['last_page']
            ]
        ]);
    }
    
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::findById($request->product_id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
        
        Transaction::addItem($product, $request->quantity);
        
        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang'
        ]);
    }
    
    public function getCart()
    {
        return response()->json([
            'success' => true,
            'cart' => Transaction::getCart(),
            'total' => Transaction::getTotal()
        ]);
    }
    
    public function updateCart(Request $request)
    {
        $request->validate([
            'index' => 'required|integer',
            'quantity' => 'required|integer|min:0'
        ]);
        
        $result = Transaction::updateItem($request->index, $request->quantity);
        
        return response()->json([
            'success' => $result
        ]);
    }
    
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'index' => 'required|integer'
        ]);
        
        $result = Transaction::removeItem($request->index);
        
        return response()->json([
            'success' => $result
        ]);
    }
    
    public function checkout()
    {
        $receipt = Transaction::getReceipt();
        
        if (!$receipt) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ]);
        }
        
        $html = view('components.receipt', compact('receipt'))->render();
        
        return response()->json([
            'success' => true,
            'receipt' => $html
        ]);
    }
    
    public function resetTransaction()
    {
        Transaction::reset();
        
        return response()->json([
            'success' => true
        ]);
    }
}