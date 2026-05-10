<div class="receipt">
    <div class="receipt-header">
        <div class="shop-name">FreshMart</div>
        <div class="shop-tagline">Bahan Makanan Segar & Premium</div>
        <div class="receipt-divider"></div>
        <div class="receipt-info">
            <span>No. Transaksi: #{{ rand(1000, 9999) }}</span>
            <span>Waktu: {{ $receipt['date'] }}</span>
        </div>
    </div>
    
    <div class="receipt-body">
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt['items'] as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item['name'] }}</div>
                        <div class="item-price">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                    </td>
                    <td style="text-align: center">{{ $item['quantity'] }}</td>
                    <td style="text-align: right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="receipt-divider"></div>
        
        <div class="total-row">
            <span>TOTAL BELANJA</span>
            <span class="total-amount">Rp {{ number_format($receipt['total'], 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="receipt-footer">
        <div class="barcode">|| ||| || |||| || |||</div>
        <p>Terima kasih telah berbelanja!</p>
        <p>Semoga harimu menyenangkan</p>
    </div>
</div>

<style>
    .receipt {
        text-align: left;
        color: #1e293b;
        font-family: 'Courier New', Courier, monospace;
        background: #fff;
        padding: 10px;
    }
    
    .receipt-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .shop-name {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--primary);
        letter-spacing: 1px;
    }
    
    .shop-tagline {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }
    
    .receipt-divider {
        border-top: 2px dashed #e2e8f0;
        margin: 1rem 0;
    }
    
    .receipt-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    
    .receipt-table th {
        text-align: left;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    
    .receipt-table td {
        padding: 10px 0;
        vertical-align: top;
    }
    
    .item-name {
        font-weight: 700;
    }
    
    .item-price {
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        font-weight: 900;
        font-size: 1.1rem;
    }
    
    .total-amount {
        color: var(--primary);
    }
    
    .receipt-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .barcode {
        font-size: 1.25rem;
        margin-bottom: 8px;
        letter-spacing: 2px;
        color: #1e293b;
    }
</style>
