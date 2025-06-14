@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manajemen Menu</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Menu Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter and Search -->
    <div class="row mb-3">
        <div class="col-md-4">
            <select class="form-select" id="categoryFilter">
                <option value="">Semua Kategori</option>
                <option value="Kopi">Kopi</option>
                <option value="Non-Kopi">Non-Kopi</option>
                <option value="Makanan Ringan">Makanan Ringan</option>
                <option value="Dessert">Dessert</option>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="statusFilter">
                <option value="">Semua Status</option>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" id="searchMenu" placeholder="Cari menu...">
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="row" id="menuContainer">
        @foreach($menus as $menu)
        <div class="col-md-6 col-lg-4 menu-item" 
             data-category="{{ $menu->category }}" 
             data-available="{{ $menu->is_available ? '1' : '0' }}"
             data-name="{{ strtolower($menu->name) }}">
            <div class="card mb-4">
                <div class="position-relative">
                    @if($menu->image)
                        <img src="{{ asset('images/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Availability Badge -->
                    <span class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </span>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $menu->name }}</h5>
                        <span class="badge bg-info">{{ $menu->category }}</span>
                    </div>
                    
                    <p class="card-text text-muted small">{{ Str::limit($menu->description, 100) }}</p>
                    <h4 class="text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</h4>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('penjual.menu.edit', $menu) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        
                        <button class="btn btn-{{ $menu->is_available ? 'secondary' : 'success' }} btn-sm" 
                                onclick="toggleAvailability({{ $menu->id }})">
                            <i class="fas fa-{{ $menu->is_available ? 'eye-slash' : 'eye' }} me-1"></i>
                            {{ $menu->is_available ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        
                        <button class="btn btn-danger btn-sm" onclick="deleteMenu({{ $menu->id }})">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $menus->links() }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus menu ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', filterMenus);
document.getElementById('statusFilter').addEventListener('change', filterMenus);
document.getElementById('searchMenu').addEventListener('input', filterMenus);

function filterMenus() {
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchMenu').value.toLowerCase();
    
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        const itemCategory = item.dataset.category;
        const itemStatus = item.dataset.available;
        const itemName = item.dataset.name;
        
        let show = true;
        
        if (category && itemCategory !== category) show = false;
        if (status && itemStatus !== status) show = false;
        if (search && !itemName.includes(search)) show = false;
        
        item.style.display = show ? 'block' : 'none';
    });
}

// Toggle availability
function toggleAvailability(menuId) {
    fetch(`/penjual/menu/${menuId}/toggle-availability`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan');
        }
    });
}

// Delete menu
function deleteMenu(menuId) {
    document.getElementById('deleteForm').action = `/penjual/menu/${menuId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
