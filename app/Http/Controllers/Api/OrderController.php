<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    // GET /api/orders
    public function index()
    {
        $orders = Order::with(['customer', 'services'])->latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Daftar transaksi laundry berhasil dimuat',
            'data' => OrderResource::collection($orders)
        ], 200);
    }
    // POST /api/orders
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $totalPrice = 0;
            // 1. Buat kepala transaksi (orders)
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'invoice_code' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
                'order_date' => now(),
                'completion_date' => $request->completion_date,
                'status' => 'pending',
                'total_price' => 0
            ]);
            // 2. Iterasi dan simpan detail layanan ke pivot table (order_details)
            foreach ($request->services as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->price_per_kg * $item['qty'];
                $totalPrice += $subtotal;
                $order->services()->attach($service->id, [
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ]);
            }
            // 3. Update total_price di tabel orders
            $order->update(['total_price' => $totalPrice]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Transaksi laundry berhasil dibuat',
                'data' => new OrderResource($order->load(['customer', 'services']))
            ], 201); // 201 Created
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
    // GET /api/orders/{id}
    public function show($id)
    {
        $order = Order::with(['customer', 'services'])->find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi laundry tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Detail transaksi berhasil ditemukan',
            'data' => new OrderResource($order)
        ], 200);
    }
    // PATCH /api/orders/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed'
        ]);
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi laundry tidak ditemukan'
            ], 404);
        }
        $order->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => 'Status laundry berhasil diperbarui',
            'data' => new OrderResource($order->load(['customer', 'services']))
        ], 200);
    }
}

