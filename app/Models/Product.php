<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['id', 'name', 'price', 'photo'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    private static $storageFile = 'products.json';

    // Load data dari JSON
    public static function loadData()
    {
        if (!Storage::exists(self::$storageFile)) {
            return [];
        }
        
        $content = Storage::get(self::$storageFile);
        $data = json_decode($content, true);
        
        return $data ?: [];
    }

    // Save data ke JSON
    public static function saveData($products)
    {
        Storage::put(self::$storageFile, json_encode($products, JSON_PRETTY_PRINT));
    }

    // Get all products with search and pagination
    public static function paginate($search = '', $perPage = 10, $page = 1)
    {
        $products = self::loadData();
        $collection = collect($products);

        if ($search) {
            $collection = $collection->filter(function($item) use ($search) {
                return str_contains(strtolower($item['name']), strtolower($search));
            });
        }

        $total = $collection->count();
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values()->map(function($item) {
            return (object) $item;
        });

        return [
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => (int) $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    // Get all products
    public static function getAll()
    {
        $products = self::loadData();
        return collect($products)->map(function($item) {
            return (object) $item;
        });
    }

    // Find product by ID
    public static function findById($id)
    {
        $products = self::loadData();
        if (isset($products[$id])) {
            return (object) $products[$id];
        }
        return null;
    }

    // Create product
    public static function createProduct($data)
    {
        $products = self::loadData();
        $id = uniqid();
        
        $products[$id] = [
            'id' => $id,
            'name' => $data['name'],
            'price' => (int) $data['price'],
            'photo' => $data['photo'] ?? ''
        ];
        
        self::saveData($products);
        return $id;
    }

    // Update product
    public static function updateProduct($id, $data)
    {
        $products = self::loadData();
        
        if (!isset($products[$id])) {
            return false;
        }
        
        $products[$id]['name'] = $data['name'];
        $products[$id]['price'] = (int) $data['price'];
        
        if (isset($data['photo']) && $data['photo']) {
            // Hapus foto lama
            if ($products[$id]['photo'] && Storage::disk('public')->exists($products[$id]['photo'])) {
                Storage::disk('public')->delete($products[$id]['photo']);
            }
            $products[$id]['photo'] = $data['photo'];
        }
        
        self::saveData($products);
        return true;
    }

    // Delete product
    public static function deleteProduct($id)
    {
        $products = self::loadData();
        
        if (isset($products[$id])) {
            // Hapus foto
            if ($products[$id]['photo'] && Storage::disk('public')->exists($products[$id]['photo'])) {
                Storage::disk('public')->delete($products[$id]['photo']);
            }
            unset($products[$id]);
            self::saveData($products);
            return true;
        }
        
        return false;
    }
}