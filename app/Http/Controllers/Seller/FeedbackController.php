<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $rating = $request->get('rating');
        $status = $request->get('status'); // responded, unresponded
        $period = $request->get('period', '30'); // days
        
        $query = Feedback::with(['order', 'user', 'response.user'])
                        ->whereHas('order', function($q) use ($period) {
                            $q->where('created_at', '>=', Carbon::now()->subDays($period));
                        });
        
        if ($rating) {
            $query->where('rating', $rating);
        }
        
        if ($status === 'responded') {
            $query->has('response');
        } elseif ($status === 'unresponded') {
            $query->doesntHave('response');
        }
        
        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get summary statistics
        $summary = $this->getFeedbackSummary($period);
        
        return view('penjual.feedback.index', compact('feedbacks', 'summary', 'rating', 'status', 'period'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['order.orderItems.menu', 'user', 'response.user']);
        return view('penjual.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        $request->validate([
            'response' => 'required|string|max:1000'
        ]);

        // Check if feedback already has a response
        if ($feedback->hasResponse()) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback ini sudah memiliki tanggapan'
            ]);
        }

        FeedbackResponse::create([
            'feedback_id' => $feedback->id,
            'user_id' => auth()->id(),
            'response' => $request->response
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tanggapan berhasil dikirim'
        ]);
    }

    public function updateResponse(Request $request, Feedback $feedback)
    {
        $request->validate([
            'response' => 'required|string|max:1000'
        ]);

        $response = $feedback->response;
        
        if (!$response) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggapan tidak ditemukan'
            ]);
        }

        // Only allow the original responder to update
        if ($response->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah tanggapan ini'
            ]);
        }

        $response->update(['response' => $request->response]);

        return response()->json([
            'success' => true,
            'message' => 'Tanggapan berhasil diperbarui'
        ]);
    }

    public function analytics()
    {
        $periods = [7, 30, 90];
        $analytics = [];
        
        foreach ($periods as $period) {
            $analytics[$period] = $this->getFeedbackSummary($period);
        }
        
        // Get rating trends
        $ratingTrends = $this->getRatingTrends();
        
        // Get popular feedback topics
        $topicAnalysis = $this->getTopicAnalysis();
        
        return view('penjual.feedback.analytics', compact('analytics', 'ratingTrends', 'topicAnalysis'));
    }

    private function getFeedbackSummary($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $feedbacks = Feedback::whereHas('order', function($q) use ($startDate) {
            $q->where('created_at', '>=', $startDate);
        });
        
        $total = $feedbacks->count();
        $avgRating = $feedbacks->avg('rating') ?: 0;
        $responded = $feedbacks->has('response')->count();
        $unresponded = $total - $responded;
        
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $feedbacks->clone()->where('rating', $i)->count();
        }
        
        return [
            'total' => $total,
            'average_rating' => round($avgRating, 2),
            'responded' => $responded,
            'unresponded' => $unresponded,
            'response_rate' => $total > 0 ? round(($responded / $total) * 100, 1) : 0,
            'rating_distribution' => $ratingDistribution
        ];
    }
    
    private function getRatingTrends()
    {
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $avgRating = Feedback::whereHas('order', function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })->avg('rating') ?: 0;
            
            $trends[] = [
                'date' => $date->format('M d'),
                'rating' => round($avgRating, 2)
            ];
        }
        
        return $trends;
    }
    
    private function getTopicAnalysis()
    {
        // Simple keyword analysis
        $keywords = ['kopi', 'latte', 'service', 'rasa', 'pelayanan', 'cepat', 'lambat', 'enak', 'kurang'];
        $analysis = [];
        
        foreach ($keywords as $keyword) {
            $count = Feedback::where('comment', 'LIKE', "%{$keyword}%")->count();
            if ($count > 0) {
                $analysis[] = ['keyword' => $keyword, 'count' => $count];
            }
        }
        
        return collect($analysis)->sortByDesc('count')->take(10);
    }
}
