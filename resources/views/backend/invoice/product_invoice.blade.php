@extends('admin_dashboard')
@section('admin')

<div class="content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Invoice</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Customer Invoice</h4>
                </div>
            </div>
        </div>     
        <!-- end page title --> 

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="logo" height="40">
                            </div>
                            <div class="col-6 text-end">
                                <h4 class="m-0">INVOICE #{{ str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) }}</h4>
                                <p class="mb-0 text-muted">{{ date('F j, Y') }}</p>
                            </div>
                        </div>

                        <!-- Customer and Invoice Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Bill To:</h5>
                                <address class="mb-0">
                                    <strong>{{ $customer->name }}</strong><br>
                                    {{ $customer->address }}<br>
                                    {{ $customer->city }}<br>
                                    <abbr title="Phone">P:</abbr> {{ $customer->phone }}<br>
                                    <abbr title="Email">E:</abbr> {{ $customer->email }}
                                </address>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless float-end" style="width: auto">
                                        <tbody>
                                            <tr>
                                                <th class="text-start">Invoice Date:</th>
                                                <td class="text-end">{{ date('F j, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Status:</th>
                                                <td class="text-end"><span class="badge bg-warning">Pending</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Due Date:</th>
                                                <td class="text-end">{{ date('F j, Y', strtotime('+7 days')) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Products Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Item Description</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sl = 1;
                                                $subtotal = 0;
                                                $taxRate = config('laravel-cart.tax_rate', 0.18);
                                            @endphp

                                            @foreach($cart->items as $item)
                                            <tr>
                                                <td class="text-center">{{ $sl++ }}</td>
                                                <td>
                                                    <strong>{{ $item->itemable->product_name }}</strong>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">₹{{ number_format($item->itemable->getPrice() / 100, 2) }}</td>
                                                <td class="text-end">₹{{ number_format(($item->itemable->getPrice() * $item->quantity) / 100, 2) }}</td>
                                            </tr>
                                            @php
                                                $subtotal += ($item->itemable->getPrice() * $item->quantity) / 100;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Totals Section -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="clearfix pt-3">
                                    <h6>Notes & Terms</h6>
                                    <ul class="ps-3">
                                        <li>Goods sold are not returnable</li>
                                        <li>Payment due within 7 days</li>
                                        <li>Please include invoice number with payment</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-end">
                                    <table class="table table-sm table-borderless" style="width: auto">
                                        <tbody>
                                            <tr>
                                                <th class="text-start">Subtotal:</th>
                                                <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            @if(isset($discountAmount) && $discountAmount > 0)
                                            <tr>
                                                <th class="text-start">Discount:</th>
                                                <td class="text-end text-danger">-₹{{ number_format($discountAmount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th class="text-start">Tax ({{ $taxRate * 100 }}%):</th>
                                                <td class="text-end">₹{{ number_format($subtotal * $taxRate, 2) }}</td>
                                            </tr>
                                            <tr class="border-top">
                                                @php
                                                    $total = ($subtotal + ($subtotal * $taxRate)) - ($discountAmount ?? 0);
                                                @endphp
                                                <th class="text-start">Total:</th>
                                                <td class="text-end fw-bold">₹{{ number_format($total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 mb-1">
                            <div class="text-end d-print-none">
                                <a href="javascript:window.print()" class="btn btn-primary">
                                    <i class="mdi mdi-printer me-1"></i> Print
                                </a>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#invoice-modal">
                                    <i class="mdi mdi-file-document me-1"></i> Create Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div id="invoice-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Complete Invoice</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3>Invoice for {{ $customer->name }}</h3>
                    <h4 class="text-primary">Total Amount: ₹{{ number_format($total, 2) }}</h4>
                </div>

                <form method="post" action="{{url('/final-invoice') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="" disabled selected>Select Payment Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Due">Due</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount Paid</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="pay" class="form-control" placeholder="Enter amount" 
                                   min="0" max="{{ $total }}" 
                                   step="1" required>
                        </div>
                        <small class="text-muted">Leave as 0 for full due</small>
                    </div>

                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="order_date" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="order_status" value="pending">
                    <input type="hidden" name="total_products" value="{{ $cart->items->count() }}">
                    <input type="hidden" name="sub_total" value="{{ $subtotal }}">
                    <input type="hidden" name="vat" value="{{ $subtotal * $taxRate }}">
                    @if(isset($discountAmount))
                    <input type="hidden" name="discount_amount" value="{{ $discountAmount }}">
                    @endif
                    <input type="hidden" name="total" value="{{ $total }}">

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-check-all me-1"></i> Complete Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th {
        background-color: #f8f9fa;
        border-bottom-width: 1px;
    }
    .table-bordered {
        border: 1px solid #e9ecef;
    }
    .table-sm td, .table-sm th {
        padding: 0.3rem 0.5rem;
    }
    address {
        margin-bottom: 0;
        font-style: normal;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.5em;
    }
</style>

@endsection