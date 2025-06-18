<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'customer', 'order'])->latest()->get();
        return view('owner.feedback', compact('feedbacks'));
    }
}