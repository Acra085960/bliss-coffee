<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders')->with('error', 'Feedback hanya bisa diberikan untuk pesanan yang sudah selesai.');
        }

        // Check if feedback already exists
        if ($order->hasFeedback()) {
            return redirect()->route('customer.orders')->with('error', 'Feedback untuk pesanan ini sudah diberikan.');
        }

        return view('customer.feedback.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders')->with('error', 'Feedback hanya bisa diberikan untuk pesanan yang sudah selesai.');
        }

        // Check if feedback already exists
        if ($order->hasFeedback()) {
            return redirect()->route('customer.orders')->with('error', 'Feedback untuk pesanan ini sudah diberikan.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        Feedback::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('customer.orders')->with('success', 'Terima kasih atas feedback Anda!');
    }
}
