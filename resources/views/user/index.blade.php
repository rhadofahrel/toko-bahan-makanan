@extends('layouts.app')

@section('content')
    <div class="hero">
        <h1>Bahan Makanan Segar</h1>
        <p>Kualitas terbaik untuk hidangan keluarga Anda.</p>
    </div>

    <div class="products-grid" id="products-container">
        @foreach($products as $product)
            <div class="product-card">
                <div class="product-image">
                    @if($product->photo)
                        <img src="{{ asset('public/products/' . basename($product->photo)) }}" alt="{{ $product->name }}"
                            onerror="this.src='https://via.placeholder.com/200'">
                    @else
                        <div class="placeholder-img">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="buy-actions">
                        <div class="qty-input">
                            <button class="qty-control minus"><i class="fas fa-minus"></i></button>
                            <input type="number" value="1" min="1" class="qty-value" id="qty-{{ $product->id }}">
                            <button class="qty-control plus"><i class="fas fa-plus"></i></button>
                        </div>
                        <button class="btn btn-primary add-to-cart" data-id="{{ $product->id }}">
                            <i class="fas fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cart Modal -->
    <div class="modal-overlay" id="cart-modal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Keranjang Belanja</h2>
                <button class="modal-close" id="close-cart">&times;</button>
            </div>
            <div id="cart-items-container">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span id="cart-total-amount">Rp 0</span>
                </div>
                <div class="cart-actions">
                    <button class="btn btn-outline" id="reset-cart">Kosongkan</button>
                    <button class="btn btn-primary" id="checkout-btn">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .hero {
                text-align: center;
                padding: 4rem 1rem;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                border-radius: var(--radius);
                margin-bottom: 3rem;
                box-shadow: var(--shadow-lg);
            }

            .hero h1 {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            .products-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                /* Mobile default */
                gap: 1.5rem;
            }

            @media (min-width: 640px) {
                .products-grid {
                    grid-template-columns: repeat(3, 1fr);
                    /* Tablet */
                }
            }

            @media (min-width: 1024px) {
                .products-grid {
                    grid-template-columns: repeat(4, 1fr);
                    /* Desktop */
                }
            }

            .product-card {
                background: var(--surface);
                border-radius: var(--radius);
                overflow: hidden;
                box-shadow: var(--shadow);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                border: 1px solid var(--border);
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
                border-color: var(--primary);
            }

            .product-image {
                height: 180px;
                background: #f8fafc;
                display: flex;
                align-items: center;
                justify-content: center;
                border-bottom: 1px solid var(--border);
                overflow: hidden;
            }

            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s;
            }

            .product-card:hover .product-image img {
                transform: scale(1.05);
            }

            .placeholder-img {
                font-size: 3rem;
                color: #cbd5e1;
            }

            .product-info {
                padding: 1rem;
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .product-info h3 {
                font-size: 1rem;
                font-weight: 700;
                color: var(--text-main);
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                height: 2.4rem;
            }

            .product-info .price {
                color: var(--primary);
                font-weight: 800;
                font-size: 1.1rem;
            }

            .buy-actions {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                margin-top: auto;
            }

            .qty-input {
                display: flex;
                align-items: center;
                border: 1px solid var(--border);
                border-radius: 8px;
                overflow: hidden;
            }

            .qty-control {
                width: 32px;
                height: 32px;
                background: #f8fafc;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-main);
                transition: all 0.2s;
            }

            .qty-control:hover {
                background: var(--border);
            }

            .qty-value {
                flex: 1;
                width: 100%;
                text-align: center;
                border: none;
                border-left: 1px solid var(--border);
                border-right: 1px solid var(--border);
                font-weight: 600;
                font-size: 0.9rem;
                background: white;
                padding: 4px 0;
            }

            .qty-value::-webkit-inner-spin-button,
            .qty-value::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .product-info .btn {
                width: 100%;
            }

            /* Cart Modal specific */
            .cart-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.25rem 0;
                border-bottom: 1px solid var(--border);
            }

            .cart-item-info {
                flex: 1;
            }

            .cart-item-info h4 {
                margin-bottom: 0.25rem;
                font-weight: 700;
            }

            .cart-item-info .price-tag {
                color: var(--text-muted);
                font-size: 0.85rem;
            }

            .cart-item-info .subtotal-tag {
                color: var(--primary);
                font-weight: 700;
                font-size: 0.95rem;
                margin-top: 0.25rem;
            }

            .cart-item-qty {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .cart-qty-input {
                width: 40px;
                text-align: center;
                border: 1px solid var(--border);
                border-radius: 4px;
                padding: 2px 0;
                font-weight: 600;
            }

            .qty-btn {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                border: 1px solid var(--border);
                background: white;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
                font-size: 0.8rem;
            }

            .qty-btn:hover {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            .cart-footer {
                margin-top: 2rem;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .cart-total {
                display: flex;
                justify-content: space-between;
                font-size: 1.25rem;
                font-weight: 700;
                border-top: 2px solid var(--border);
                padding-top: 1rem;
            }

            .cart-actions {
                display: flex;
                gap: 1rem;
            }

            .cart-actions .btn {
                flex: 1;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cartModal = document.getElementById('cart-modal');
                const openCartBtn = document.getElementById('open-cart');
                const closeCartBtn = document.getElementById('close-cart');
                const cartItemsContainer = document.getElementById('cart-items-container');
                const cartTotalAmount = document.getElementById('cart-total-amount');

                // Quantity controls on product cards
                document.querySelectorAll('.qty-control').forEach(btn => {
                    btn.onclick = function () {
                        const parent = this.closest('.qty-input');
                        const input = parent.querySelector('.qty-value');
                        let val = parseInt(input.value);
                        if (this.classList.contains('plus')) {
                            input.value = val + 1;
                        } else if (this.classList.contains('minus') && val > 1) {
                            input.value = val - 1;
                        }
                    };
                });

                // Open/Close Cart
                openCartBtn.onclick = () => {
                    loadCart();
                    cartModal.style.display = 'flex';
                };
                closeCartBtn.onclick = () => cartModal.style.display = 'none';
                window.onclick = (e) => { if (e.target == cartModal) cartModal.style.display = 'none'; };

                // Load Cart Data
                function loadCart() {
                    fetch('{{ route('cart.get') }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                renderCart(data.cart, data.total);
                                updateCartBadge();
                            }
                        });
                }

                function renderCart(items, total) {
                    if (items.length === 0) {
                        cartItemsContainer.innerHTML = '<div style="text-align:center; padding:3rem; color:var(--text-muted)"><i class="fas fa-shopping-basket" style="font-size:3rem; margin-bottom:1rem; opacity:0.3"></i><p>Keranjang Anda masih kosong.</p></div>';
                        cartTotalAmount.textContent = 'Rp 0';
                        return;
                    }

                    let html = '';
                    items.forEach((item, index) => {
                        html += `
                                    <div class="cart-item">
                                        <div class="cart-item-info">
                                            <h4>${item.name}</h4>
                                            <div class="price-tag">Rp ${new Intl.NumberFormat('id-ID').format(item.price)} / unit</div>
                                            <div class="subtotal-tag">Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</div>
                                        </div>
                                        <div class="cart-item-qty">
                                            <button class="qty-btn" onclick="updateQty(${index}, ${item.quantity - 1})"><i class="fas fa-minus"></i></button>
                                            <input type="number" value="${item.quantity}" class="cart-qty-input" readonly>
                                            <button class="qty-btn" onclick="updateQty(${index}, ${item.quantity + 1})"><i class="fas fa-plus"></i></button>
                                            <button class="qty-btn" style="color:#ef4444; border-color:#fee2e2; margin-left:8px" onclick="removeItem(${index})"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                `;
                    });
                    cartItemsContainer.innerHTML = html;
                    cartTotalAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                }

                // Add to Cart
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.onclick = function () {
                        const id = this.getAttribute('data-id');
                        const qty = parseInt(document.getElementById('qty-' + id).value);

                        fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ product_id: id, quantity: qty })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Ditambahkan!',
                                        text: data.message,
                                        timer: 2000,
                                        showConfirmButton: false,
                                        toast: true,
                                        position: 'top-end'
                                    });
                                    updateCartBadge();
                                    // Reset input qty back to 1
                                    document.getElementById('qty-' + id).value = 1;
                                }
                            });
                    };
                });

                // Update Qty
                window.updateQty = (index, qty) => {
                    if (qty < 1) return removeItem(index);

                    fetch('{{ route('cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ index: index, quantity: qty })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) loadCart();
                        });
                };

                // Remove Item
                window.removeItem = (index) => {
                    Swal.fire({
                        title: 'Hapus item?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route('cart.remove') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ index: index })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) loadCart();
                                });
                        }
                    });
                };

                // Reset Cart
                document.getElementById('reset-cart').onclick = () => {
                    Swal.fire({
                        title: 'Kosongkan keranjang?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Kosongkan!'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch('{{ route('cart.reset') }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) loadCart();
                                });
                        }
                    });
                };

                // Checkout
                document.getElementById('checkout-btn').onclick = () => {
                    fetch('{{ route('checkout') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Pembayaran Berhasil!',
                                    html: data.receipt,
                                    width: '450px',
                                    confirmButtonText: 'Selesai',
                                    confirmButtonColor: 'var(--primary)',
                                    padding: '2rem'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Oops', data.message, 'error');
                            }
                        });
                };
            });
        </script>
    @endpush
@endsection