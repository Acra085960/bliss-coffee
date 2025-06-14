@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Feedback Pelanggan</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjual.feedback.analytics') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $summary['total'] }}</h3>
                    <small>Total Feedback</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $summary['average_rating'] }}</h3>
                    <small>Rating Rata-rata</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $summary['responded'] }}</h3>
                    <small>Sudah Ditanggapi</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $summary['unresponded'] }}</h3>
                    <small>Belum Ditanggapi</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h3 class="text-secondary">{{ $summary['response_rate'] }}%</h3>
                    <small>Response Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <div class="rating-stars text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $summary['average_rating'])
                                <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $summary['average_rating'])
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <small>Rating Visual</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-2">
            <select class="form-select" id="ratingFilter" onchange="applyFilters()">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ $rating == $i ? 'selected' : '' }}>
                        {{ $i }} ⭐
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="statusFilter" onchange="applyFilters()">
                <option value="">Semua Status</option>
                <option value="unresponded" {{ $status == 'unresponded' ? 'selected' : '' }}>Belum Ditanggapi</option>
                <option value="responded" {{ $status == 'responded' ? 'selected' : '' }}>Sudah Ditanggapi</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="periodFilter" onchange="applyFilters()">
                <option value="7" {{ $period == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="30" {{ $period == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="90" {{ $period == '90' ? 'selected' : '' }}>90 Hari Terakhir</option>
            </select>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="row">
        @forelse($feedbacks as $feedback)
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $feedback->user->name }}</strong>
                        <small class="text-muted">- Pesanan #{{ $feedback->order->id }}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-{{ $feedback->rating_color }} me-2">
                            {{ $feedback->rating }}/5 ⭐
                        </span>
                        <span class="text-muted">{{ $feedback->created_at->diffForHumans() }}</span>
                        @if($feedback->hasResponse())
                            <span class="badge bg-success ms-2">Ditanggapi</span>
                        @else
                            <span class="badge bg-warning ms-2">Belum Ditanggapi</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Order Details -->
                            <div class="mb-3">
                                <strong>Item Pesanan:</strong>
                                <div class="ms-3">
                                    @foreach($feedback->order->orderItems as $item)
                                        <span class="badge bg-light text-dark me-1">
                                            {{ $item->menu->name ?? 'N/A' }} ({{ $item->quantity }}x)
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Customer Feedback -->
                            <div class="mb-3">
                                <strong>Feedback:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    <div class="mb-2">
                                        {{ $feedback->rating_stars }}
                                    </div>
                                    @if($feedback->comment)
                                        <p class="mb-0">{{ $feedback->comment }}</p>
                                    @else
                                        <p class="text-muted mb-0">Tidak ada komentar</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Response Section -->
                            @if($feedback->response)
                                <div class="mb-3">
                                    <strong>Tanggapan Anda:</strong>
                                    <div class="bg-primary bg-opacity-10 p-3 rounded mt-2">
                                        <p class="mb-1">{{ $feedback->response->response }}</p>
                                        <small class="text-muted">
                                            Oleh {{ $feedback->response->user->name }} - 
                                            {{ $feedback->response->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if(!$feedback->hasResponse())
                                <!-- Response Form -->
                                <form class="response-form" data-feedback-id="{{ $feedback->id }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Tanggapi Feedback:</strong></label>
                                        <textarea class="form-control" name="response" rows="3" 
                                                placeholder="Tulis tanggapan Anda untuk pelanggan..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-reply me-1"></i>Kirim Tanggapan
                                    </button>
                                </form>
                            @else
                                <!-- Edit Response -->
                                <button class="btn btn-outline-primary btn-sm w-100" 
                                        onclick="editResponse({{ $feedback->id }})">
                                    <i class="fas fa-edit me-1"></i>Edit Tanggapan
                                </button>
                            @endif
                            
                            <a href="{{ route('penjual.feedback.show', $feedback) }}" 
                               class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                <i class="fas fa-eye me-1"></i>Detail Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <h4>Belum Ada Feedback</h4>
                <p class="text-muted">Belum ada feedback dari pelanggan untuk periode yang dipilih.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $feedbacks->appends(request()->query())->links() }}
    </div>
</div>

<!-- Edit Response Modal -->
<div class="modal fade" id="editResponseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editResponseForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tanggapan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editFeedbackId">
                    <div class="mb-3">
                        <label class="form-label">Tanggapan:</label>
                        <textarea class="form-control" id="editResponseText" name="response" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Tanggapan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const rating = document.getElementById('ratingFilter').value;
    const status = document.getElementById('statusFilter').value;
    const period = document.getElementById('periodFilter').value;
    
    const url = new URL(window.location);
    
    if (rating) url.searchParams.set('rating', rating);
    else url.searchParams.delete('rating');
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    if (period) url.searchParams.set('period', period);
    else url.searchParams.delete('period');
    
    window.location.href = url.toString();
}

// Handle response form submission
document.querySelectorAll('.response-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const feedbackId = this.dataset.feedbackId;
        const formData = new FormData(this);
        
        fetch(`/penjual/feedback/${feedbackId}/respond`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Terjadi kesalahan', 'error');
        });
    });
});

function editResponse(feedbackId) {
    // Get current response text
    const responseElement = document.querySelector(`[data-feedback-id="${feedbackId}"]`)
                                   .closest('.card')
                                   .querySelector('.bg-primary.bg-opacity-10 p');
    
    if (responseElement) {
        const currentResponse = responseElement.firstChild.textContent.trim();
        document.getElementById('editFeedbackId').value = feedbackId;
        document.getElementById('editResponseText').value = currentResponse;
        new bootstrap.Modal(document.getElementById('editResponseModal')).show();
    }
}

// Handle edit response form
document.getElementById('editResponseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const feedbackId = document.getElementById('editFeedbackId').value;
    const formData = new FormData(this);
    
    fetch(`/penjual/feedback/${feedbackId}/update-response`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editResponseModal')).hide();
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    });
});

function showToast(message, type) {
    const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const toast = document.createElement('div');
    toast.className = `alert ${toastClass} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
