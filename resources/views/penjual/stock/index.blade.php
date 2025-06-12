@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Pengelolaan Stok</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjual.stock.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Item Stok
            </a>
            <a href="{{ route('penjual.stock.low-stock') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $summary['total_items'] }}</h3>
                    <small>Total Item</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $summary['low_stock'] }}</h3>
                    <small>Stok Menipis</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ $summary['out_of_stock'] }}</h3>
                    <small>Stok Habis</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">{{ $summary['categories']->count() }}</h3>
                    <small>Kategori</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-select" id="categoryFilter" onchange="applyFilters()">
                <option value="">Semua Kategori</option>
                @foreach($summary['categories'] as $cat)
                    <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="statusFilter" onchange="applyFilters()">
                <option value="">Semua Status</option>
                <option value="low" {{ $status == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                <option value="out" {{ $status == 'out' ? 'selected' : '' }}>Stok Habis</option>
                <option value="overstock" {{ $status == 'overstock' ? 'selected' : '' }}>Overstock</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-info" onclick="toggleBulkMode()">
                <i class="fas fa-edit me-1"></i>Mode Bulk Update
            </button>
        </div>
    </div>

    <!-- Bulk Update Panel (Hidden by default) -->
    <div id="bulkPanel" class="alert alert-info" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Mode Bulk Update</strong> - Pilih item untuk update massal
            </div>
            <div class="btn-group">
                <button class="btn btn-success btn-sm" onclick="showBulkModal()">Update Terpilih</button>
                <button class="btn btn-secondary btn-sm" onclick="toggleBulkMode()">Keluar</button>
            </div>
        </div>
    </div>

    <!-- Stock Items -->
    <div class="row">
        @forelse($stocks as $stock)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 stock-card border-{{ $stock->stock_status == 'out_of_stock' ? 'danger' : ($stock->stock_status == 'low_stock' ? 'warning' : 'light') }}">
                <!-- Bulk Checkbox (Hidden by default) -->
                <div class="bulk-checkbox" style="display: none;">
                    <input type="checkbox" class="form-check-input position-absolute bulk-item" 
                           style="top: 10px; left: 10px; z-index: 10;" 
                           value="{{ $stock->id }}"
                           data-name="{{ $stock->name }}"
                           data-current="{{ $stock->current_stock }}"
                           data-unit="{{ $stock->unit }}">
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $stock->name }}</h5>
                            <span class="badge bg-secondary">{{ $stock->category }}</span>
                        </div>
                        <span class="badge bg-{{ $stock->stock_status == 'out_of_stock' ? 'danger' : ($stock->stock_status == 'low_stock' ? 'warning' : 'success') }}">
                            {{ $stock->stock_status == 'out_of_stock' ? 'Habis' : ($stock->stock_status == 'low_stock' ? 'Menipis' : 'Normal') }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Stok Saat Ini:</small>
                            <strong>{{ number_format($stock->current_stock, 2) }} {{ $stock->unit }}</strong>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-{{ $stock->stock_status == 'out_of_stock' ? 'danger' : ($stock->stock_status == 'low_stock' ? 'warning' : 'success') }}" 
                                 role="progressbar" 
                                 style="width: {{ min(100, $stock->stock_percentage) }}%">
                                {{ number_format($stock->stock_percentage, 1) }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Min: {{ $stock->minimum_stock }}</small>
                            <small class="text-muted">Max: {{ $stock->maximum_stock }}</small>
                        </div>
                    </div>

                    @if($stock->description)
                    <p class="card-text text-muted small">{{ Str::limit($stock->description, 60) }}</p>
                    @endif

                    @if($stock->price_per_unit)
                    <p class="text-primary mb-2">
                        <strong>Rp {{ number_format($stock->price_per_unit, 0, ',', '.') }}/{{ $stock->unit }}</strong>
                    </p>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="d-flex gap-1">
                        <button class="btn btn-success btn-sm flex-fill" onclick="showStockModal({{ $stock->id }}, 'in')">
                            <i class="fas fa-plus"></i> Masuk
                        </button>
                        <button class="btn btn-warning btn-sm flex-fill" onclick="showStockModal({{ $stock->id }}, 'out')">
                            <i class="fas fa-minus"></i> Keluar
                        </button>
                        <button class="btn btn-info btn-sm flex-fill" onclick="showStockModal({{ $stock->id }}, 'adjustment')">
                            <i class="fas fa-edit"></i> Adjust
                        </button>
                        <a href="{{ route('penjual.stock.edit', $stock) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-4x text-muted mb-3"></i>
                <h4>Tidak ada item stok</h4>
                <p class="text-muted">Belum ada item stok yang ditambahkan.</p>
                <a href="{{ route('penjual.stock.create') }}" class="btn btn-primary">Tambah Item Pertama</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $stocks->appends(request()->query())->links() }}
    </div>

    <!-- Recent Movements -->
    @if($recentMovements->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pergerakan Stok Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Item</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Alasan</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('H:i d/m') }}</td>
                                    <td>{{ $movement->stock->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movement->type == 'in' ? 'success' : ($movement->type == 'out' ? 'warning' : 'info') }}">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }} {{ $movement->stock->unit }}</td>
                                    <td>{{ $movement->reason }}</td>
                                    <td>{{ $movement->user->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="stockForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalTitle">Update Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="stockId" name="stock_id">
                    <input type="hidden" id="stockType" name="type">
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="stockQuantity" name="quantity" step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <select class="form-select" id="stockReason" name="reason" required>
                            <option value="">Pilih alasan</option>
                            <option value="Pembelian">Pembelian</option>
                            <option value="Penggunaan">Penggunaan</option>
                            <option value="Rusak/Kadaluarsa">Rusak/Kadaluarsa</option>
                            <option value="Koreksi Stok">Koreksi Stok</option>
                            <option value="Return">Return</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="bulkForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Update Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="bulkItems"></div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Update</label>
                        <input type="text" class="form-control" name="reason" required placeholder="Contoh: Stock opname mingguan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let bulkMode = false;

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location);
    
    if (category) url.searchParams.set('category', category);
    else url.searchParams.delete('category');
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    window.location.href = url.toString();
}

function toggleBulkMode() {
    bulkMode = !bulkMode;
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    const bulkPanel = document.getElementById('bulkPanel');
    
    checkboxes.forEach(checkbox => {
        checkbox.style.display = bulkMode ? 'block' : 'none';
    });
    
    bulkPanel.style.display = bulkMode ? 'block' : 'none';
    
    if (!bulkMode) {
        // Uncheck all when exiting bulk mode
        document.querySelectorAll('.bulk-item').forEach(cb => cb.checked = false);
    }
}

function showStockModal(stockId, type) {
    document.getElementById('stockId').value = stockId;
    document.getElementById('stockType').value = type;
    
    const titles = {
        'in': 'Stok Masuk',
        'out': 'Stok Keluar', 
        'adjustment': 'Penyesuaian Stok'
    };
    
    document.getElementById('stockModalTitle').textContent = titles[type];
    document.getElementById('stockForm').reset();
    document.getElementById('stockId').value = stockId;
    document.getElementById('stockType').value = type;
    
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}

function showBulkModal() {
    const selectedItems = Array.from(document.querySelectorAll('.bulk-item:checked'));
    
    if (selectedItems.length === 0) {
        alert('Pilih minimal satu item');
        return;
    }
    
    let html = '';
    selectedItems.forEach(item => {
        html += `
            <div class="row mb-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">${item.dataset.name}</label>
                    <small class="text-muted d-block">Current: ${item.dataset.current} ${item.dataset.unit}</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe</label>
                    <select class="form-select" name="updates[${item.value}][type]" required>
                        <option value="in">Masuk</option>
                        <option value="out">Keluar</option>
                        <option value="adjustment">Adjust</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="updates[${item.value}][quantity]" step="0.01" min="0.01" required>
                </div>
                <input type="hidden" name="updates[${item.value}][stock_id]" value="${item.value}">
            </div>
        `;
    });
    
    document.getElementById('bulkItems').innerHTML = html;
    new bootstrap.Modal(document.getElementById('bulkModal')).show();
}

// Handle stock form submission
document.getElementById('stockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route("penjual.stock.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(Object.fromEntries(new FormData(this)))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('stockModal')).hide();
            location.reload();
        } else {
            alert('Terjadi kesalahan');
        }
    });
});

// Handle bulk form submission
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const updates = {};
    
    // Parse form data into proper structure
    formData.forEach((value, key) => {
        if (key.startsWith('updates[')) {
            const match = key.match(/updates\[(\d+)\]\[(\w+)\]/);
            if (match) {
                const stockId = match[1];
                const field = match[2];
                if (!updates[stockId]) updates[stockId] = {};
                updates[stockId][field] = value;
            }
        }
    });
    
    fetch('{{ route("penjual.stock.bulk-update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            updates: Object.values(updates),
            reason: formData.get('reason')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('bulkModal')).hide();
            location.reload();
        } else {
            alert('Terjadi kesalahan');
        }
    });
});
</script>

<style>
.stock-card {
    transition: transform 0.2s;
}

.stock-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bulk-checkbox {
    position: relative;
}
</style>
@endsection
