{{-- filepath: /home/acra/bliss/resources/views/customer/menu.blade.php --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger px-4 py-2 rounded">
        Logout
    </button>
</form>