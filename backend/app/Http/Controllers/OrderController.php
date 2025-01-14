<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function index(Request $request)
{
    $organization = $request->user()->organization;

    if (!$organization) {
        return response()->json(['message' => 'Nie należysz do organizacji'], 403);
    }

    $orders = $organization->orders()->with('items.product')->get();

    return response()->json($orders);
}

    public function generateOrders(Request $request)
    {
        $organization = $request->user()->organization;
    
        if (!$organization) {
            return response()->json(['message' => 'Nie jesteś przypisany do organizacji'], 403);
        }
    
        // Tworzenie zamówienia
        $order = \App\Models\Order::create([
            'organization_id' => $organization->id,
            'status' => 'open',
        ]);
    
        // Losowe produkty dla zamówienia
        $products = \App\Models\Product::inRandomOrder()->take(rand(5, 10))->get();
    
        foreach ($products as $product) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 20),
            ]);
        }
    
        return response()->json(['message' => 'Zamówienie wygenerowane', 'order' => $order->load('items.product')]);
    }
    

    public function assignTaskToUser(Request $request, $orderId)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $user = $request->user();

    // Sprawdzenie, czy użytkownik jest adminem
    if ($user->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Szukanie zamówienia
    $order = Order::where('id', $orderId)
                  ->where('organization_id', $user->organization_id)
                  ->first();

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    // Przypisanie zamówienia użytkownikowi
    $order->update([
        'assigned_to' => $request->user_id,
    ]);

    return response()->json(['message' => 'Task assigned successfully']);
}

public function closeOrder($orderId)
{
    $order = Order::findOrFail($orderId);

    // Zmiana statusu zamówienia na "closed"
    $order->status = 'closed';
    $order->save();

    return response()->json(['message' => 'Zamówienie zostało zamknięte']);
}

    

}
