<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice #{{ $order->invoice_no }} | Rajput Book Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #8b0000;
            --secondary: #f8f1e5;
            --accent: #d4af37;
            --text: #333;
            --light-text: #777;
            --border: #ddd;
        }
        
        * {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            padding: 5mm;
            background-color: #f9f9f9;
            color: var(--text);
            line-height: 1.4;
            font-size: 13px;
        }
        
        .invoice-wrapper {
            max-width: 210mm;
            margin: 0 auto;
            padding: 5mm;
            background: white;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        
        .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid var(--primary);
        }
        
        .store-info h2 {
            color: var(--primary);
            font-size: 18px;
            margin: 0 0 2px 0;
        }
        
        .invoice-meta h3 {
            color: var(--primary);
            font-size: 16px;
            margin: 0 0 2px 0;
            text-align: right;
        }
        
        .details-container {
            display: flex;
            gap: 5mm;
            margin-bottom: 5mm;
        }
        
        .detail-box {
            flex: 1;
            padding: 3mm;
            background: var(--secondary);
            border-radius: 3px;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
            font-size: 12px;
        }
        
        th {
            background-color: var(--primary);
            color: white;
            padding: 2mm;
            text-align: left;
        }
        
        td {
            padding: 2mm;
            border-bottom: 1px solid var(--border);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .summary {
            width: 60mm;
            margin-left: auto;
            margin-bottom: 5mm;
            font-size: 12px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2mm;
        }
        
        .grand-total {
            font-weight: bold;
            border-top: 1px solid var(--text);
            padding-top: 2mm;
            margin-top: 2mm;
        }
        
        .footer {
            text-align: center;
            font-size: 11px;
            color: var(--light-text);
        }
        
        @media print {
            body {
                padding: 0;
                background: none;
            }
            .invoice-wrapper {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div class="store-info">
                <h2>Rajput Book Store</h2>
                <p>123 Book Street, Gwalior, MP - 474001</p>
                <p>GSTIN: 22AAAAA0000A1Z5 | Phone: 9876543210</p>
            </div>
            <div class="invoice-meta">
                <h3>INVOICE #{{ $order->invoice_no }}</h3>
                <p>Date: {{ $order->order_date }}</p>
                <p>Status: {{ ucfirst($order->order_status) }}</p>
            </div>
        </div>
        
        <div class="details-container">
            <div class="detail-box">
                <h4><i class="fas fa-user"></i> CUSTOMER</h4>
                <p><strong>{{ $order->customer->name ?? 'N/A' }}</strong></p>
                <p>{{ $order->customer->phone ?? 'N/A' }}</p>
                <p>{{ $order->customer->address ?? 'N/A' }}</p>
            </div>
            <div class="detail-box">
                <h4><i class="fas fa-wallet"></i> PAYMENT</h4>
                <p>Method: {{ ucfirst($order->payment_status) }}</p>
                <p>Paid: <span class="amount">₹{{ number_format($order->pay, 2) }}</span></p>
                <p>Due: <span class="amount">₹{{ number_format($order->due, 2) }}</span></p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Code</th>
                    <th>Qty</th>
                    <th class="amount">Unit Price</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
    @forelse($order->details ?? [] as $item)
    <tr>
        <td>{{ $item->product->product_name ?? 'Product Deleted' }}</td>
        <td>{{ $item->product->product_code ?? 'N/A' }}</td>
        <td>{{ $item->quantity }}</td>
        <td class="amount">₹{{ number_format($item->unitcost, 2) }}</td>
        <td class="amount">₹{{ number_format($item->total, 2) }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center">No items found in this order</td>
    </tr>
    @endforelse
</tbody>
        </table>
        
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span class="amount">₹{{ number_format($order->sub_total, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="summary-row">
                <span>Discount:</span>
                <span class="amount">-₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="summary-row">
                <span>Tax ({{ $order->tax_percent }}%):</span>
                <span class="amount">₹{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="summary-row grand-total">
                <span>TOTAL:</span>
                <span class="amount">₹{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for your business! • Terms: Payment due within 15 days</p>
            <p>For any queries, contact: 9876543210 or email@rajputbooks.com</p>
        </div>
    </div>
</body>
</html>