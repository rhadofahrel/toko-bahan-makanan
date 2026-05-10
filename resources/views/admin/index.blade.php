@extends('layouts.app')

@section('content')
    <div class="admin-header">
        <h1>Manajemen Produk</h1>
        <div class="header-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Cari barang...">
            </div>
            <button class="btn btn-primary" id="add-product-btn">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </button>
        </div>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="products-table-body">
                <!-- Products will be loaded here via AJAX -->
            </tbody>
        </table>
        <div class="pagination-container" id="pagination">
            <!-- Pagination links will be loaded here -->
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal-overlay" id="product-modal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 id="modal-title">Tambah Produk</h2>
                <button class="modal-close" id="close-modal">&times;</button>
            </div>
            <form id="product-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="product-id" name="id">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Beras Ramos 5kg">
                </div>
                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" id="price" name="price" required placeholder="Contoh: 75000">
                </div>
                <div class="form-group">
                    <label for="photo">Foto Produk</label>
                    <div class="image-preview-container">
                        <img id="image-preview" src="https://via.placeholder.com/150" alt="Preview">
                    </div>
                    <input type="file" id="photo" name="photo" accept="image/png, image/jpeg, image/jpg">
                    <p class="help-text">Format: JPG/PNG, Max 2MB. Biarkan kosong jika tidak ingin mengubah foto.</p>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="save-btn">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .admin-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .header-actions {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .search-box {
                position: relative;
            }

            .search-box i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-muted);
            }

            .search-box input {
                padding: 0.6rem 1rem 0.6rem 2.5rem;
                border: 1px solid var(--border);
                border-radius: 8px;
                width: 250px;
                outline: none;
                transition: border-color 0.2s;
            }

            .search-box input:focus {
                border-color: var(--primary);
            }

            .admin-card {
                background: var(--surface);
                border-radius: var(--radius);
                padding: 1.5rem;
                box-shadow: var(--shadow);
            }

            .admin-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            .admin-table th {
                padding: 1rem;
                border-bottom: 2px solid var(--border);
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                font-size: 0.8rem;
            }

            .admin-table td {
                padding: 1rem;
                border-bottom: 1px solid var(--border);
                vertical-align: middle;
            }

            .product-thumb {
                width: 60px;
                height: 60px;
                border-radius: 8px;
                object-fit: cover;
                background: #f1f5f9;
                border: 1px solid var(--border);
            }

            .pagination-container {
                margin-top: 1.5rem;
                display: flex;
                justify-content: center;
                gap: 0.5rem;
            }

            .page-btn {
                padding: 0.5rem 1rem;
                border: 1px solid var(--border);
                background: white;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .page-btn:hover {
                background: #f8fafc;
            }

            .page-btn.active {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            .image-preview-container {
                width: 100%;
                height: 150px;
                background: #f8fafc;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                border: 2px dashed var(--border);
                overflow: hidden;
            }

            .image-preview-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
            }

            .form-group input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid var(--border);
                border-radius: 8px;
                font-family: inherit;
            }

            .help-text {
                font-size: 0.8rem;
                color: var(--text-muted);
                margin-top: 0.25rem;
            }

            .form-actions {
                margin-top: 2rem;
            }

            .form-actions .btn {
                width: 100%;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const productModal = document.getElementById('product-modal');
                const productForm = document.getElementById('product-form');
                const productsTableBody = document.getElementById('products-table-body');
                const modalTitle = document.getElementById('modal-title');
                const searchInput = document.getElementById('search-input');
                const paginationContainer = document.getElementById('pagination');
                const imagePreview = document.getElementById('image-preview');
                const photoInput = document.getElementById('photo');

                let currentPage = 1;
                let searchQuery = '';
                let searchTimer;

                // Toast configuration
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                // Open Modal
                document.getElementById('add-product-btn').onclick = () => {
                    productForm.reset();
                    document.getElementById('product-id').value = '';
                    modalTitle.textContent = 'Tambah Produk';
                    imagePreview.src = 'https://via.placeholder.com/150';
                    productModal.style.display = 'flex';
                };

                document.getElementById('close-modal').onclick = () => productModal.style.display = 'none';

                // Image Preview Logic
                photoInput.onchange = function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => imagePreview.src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                };

                // Search with Debounce
                searchInput.oninput = function () {
                    clearTimeout(searchTimer);
                    searchQuery = this.value;
                    searchTimer = setTimeout(() => {
                        currentPage = 1;
                        loadProducts();
                    }, 500);
                };

                // Load Products
                function loadProducts() {
                    const url = `{{ route('admin.products.getAll') }}?search=${searchQuery}&page=${currentPage}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                renderTable(data.data);
                                renderPagination(data.pagination);
                            }
                        });
                }

                function renderTable(products) {
                    if (products.length === 0) {
                        productsTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding:2rem; color:var(--text-muted)">Barang tidak ditemukan.</td></tr>';
                        return;
                    }

                    let html = '';
                    products.forEach(p => {
                        html += `
            <tr>
                <td>
                    <img src="${p.photo ? '/products/' + p.photo : 'https://via.placeholder.com/60'}" 
                         class="product-thumb"
                         onerror="this.src='https://via.placeholder.com/60'">
                </td>
                <td><strong>${p.name}</strong></td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(p.price)}</td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="editProduct('${p.id}', '${p.name}', ${p.price}, '${p.photo}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline" style="color:red; border-color:red" onclick="deleteProduct('${p.id}')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            </tr>
        `;
                    });
                    productsTableBody.innerHTML = html;
                }

                function renderPagination(pagination) {
                    if (pagination.last_page <= 1) {
                        paginationContainer.innerHTML = '';
                        return;
                    }

                    let html = '';
                    for (let i = 1; i <= pagination.last_page; i++) {
                        html += `<button class="page-btn ${i === pagination.current_page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
                    }
                    paginationContainer.innerHTML = html;
                }

                window.goToPage = (page) => {
                    currentPage = page;
                    loadProducts();
                };

                loadProducts();

                // Save Product (Create/Update)
                productForm.onsubmit = function (e) {
                    e.preventDefault();
                    const id = document.getElementById('product-id').value;
                    const formData = new FormData(this);

                    let url = '{{ route('admin.products.store') }}';

                    if (id) {
                        url = `/admin/products/${id}`;
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toast.fire({ icon: 'success', title: data.message });
                                productModal.style.display = 'none';
                                loadProducts();
                            } else {
                                Swal.fire('Error', data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Cek validasi input Anda', 'error');
                        });
                };

                // Edit Product Helper
                window.editProduct = (id, name, price, photo) => {
                    document.getElementById('product-id').value = id;
                    document.getElementById('name').value = name;
                    document.getElementById('price').value = price;
                    modalTitle.textContent = 'Edit Produk';
                    imagePreview.src = photo ? '/products/' + photo : 'https://via.placeholder.com/150';
                    productModal.style.display = 'flex';
                };

                // Delete Product
                window.deleteProduct = (id) => {
                    Swal.fire({
                        title: 'Hapus Produk?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/products/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Toast.fire({ icon: 'success', title: data.message });
                                        loadProducts();
                                    }
                                });
                        }
                    });
                };
            });
        </script>
    @endpush
@endsection