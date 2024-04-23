<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $query = Product::query();
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('sku', 'LIKE', '%' . $searchQuery . '%');
            });
        }
        $perPage = 10; 
        $products = $query->paginate($perPage);
        return view('pos', compact('products'));
    }
    public function store(Request $request)
    {
      
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:255',
            'unit' => 'required|string|max:255',
            'unit_value' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'discount' => 'required|numeric|between:0,100',
            'tax' => 'required|numeric|between:0,100',
            'image' => 'nullable|image|max:2048',
            'variant_name' => 'array',
            'variant_value' => 'array',
            'variant_price' => 'array',
        ]);
       
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }
        $product = Product::create([
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            'unit' => $request->input('unit'),
            'unit_value' => $request->input('unit_value'),
            'selling_price' => $request->input('selling_price'),
            'purchase_price' => $request->input('purchase_price'),
            'discount' => $request->input('discount'),
            'tax' => $request->input('tax'),
            'image' => $imagePath,
        ]);
        if ($request->has('variant_name')) {
            
            $variants = [];
            for ($i = 0; $i < count($request->input('variant_name')); $i++) {
                
                if (!empty($request->input('variant_name')[$i]) &&
                    !empty($request->input('variant_value')[$i]) &&
                    !empty($request->input('variant_price')[$i])) {
                    $variants[] = [
                        'product_id' => $product->id,
                        'name' => $request->input('variant_name')[$i],
                        'value' => $request->input('variant_value')[$i],
                        'price' => $request->input('variant_price')[$i],
                    ];
                }
            }

            
            if (!empty($variants)) {
                Variant::insert($variants);
            }
        }
        return 'Product added successfully!';
    }

    public function order(Request $request)
    {
        $cartData = $request->input('cart');
        $total = $request->input('total');

        $order = Order::create([
            'total_price' => $total,
        ]);

        foreach ($cartData as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        return response()->json(['success' => 'Order placed successfully!']);
    }
    function orderList(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query = Order::query();

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $perPage = 10;
        $orders = $query->paginate($perPage);
        return view('order', compact('orders'));
    }
}
