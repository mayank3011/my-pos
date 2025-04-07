@extends('admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">
    <div class="container-fluid">
        <!-- Header with Date/Time -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">
                        <i class="mdi mdi-cart-outline me-2"></i>Point of Sale System
                    </h4>
                    <div class="text-end">
                        <div id="currentDateTime" class="badge bg-primary p-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cart Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-cart me-2"></i>Shopping Cart
                            </h5>
                            <div>
                                <button class="btn btn-sm btn-outline-danger" onclick="clearCart()">
                                    <i class="mdi mdi-cart-remove"></i> Clear Cart
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                    <tr>
                                        <td>{{ $item->itemable->product_name }}</td>
                                        <td>${{ number_format($item->itemable->getPrice() / 100, 2) }}</td>
                                        <td>
                                            <form method="post" action="{{ url('/cart-update/'.$item->id) }}" class="d-flex">
                                                @csrf
                                                <input type="number" name="qty" value="{{ $item->quantity }}" 
                                                       min="1" class="form-control form-control-sm" style="width: 60px;">
                                                <button type="submit" class="btn btn-sm btn-success ms-1">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>${{ number_format(($item->itemable->getPrice() * $item->quantity) / 100, 2) }}</td>
                                        <td>
                                            <a href="{{ url('/cart-remove/'.$item->id) }}" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Summary Section -->
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Items:</span>
                                <span>{{ $cart->items->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Tax (VAT):</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            @if(session('discount'))
                            <div class="d-flex justify-content-between mb-1 text-danger">
                                <span>Discount ({{ session('discount')['type'] == 'percentage' ? session('discount')['value'].'%' : 'Fixed' }}):</span>
                                <span>-${{ number_format($discountAmount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Discount Section -->
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white py-2">
                                <i class="mdi mdi-tag-outline me-2"></i>Apply Discount
                            </div>
                            <div class="card-body">
                                <form id="discountForm" method="post" action="{{ url('/apply-discount') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <select name="discount_type" class="form-select form-select-sm">
                                                <option value="percentage">Percentage</option>
                                                <option value="fixed">Fixed Amount</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group">
                                                <input type="number" name="discount_value" 
                                                       class="form-control form-control-sm" 
                                                       placeholder="Value" min="0">
                                                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Customer & Checkout Section -->
                        <form id="checkoutForm" method="post" action="{{ url('/create-invoice') }}">
                            @csrf
                            <div class="card border-success">
                                <div class="card-header bg-success text-white py-2">
                                    <i class="mdi mdi-account-outline me-2"></i>Customer Information
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <label class="form-label">Select Customer</label>
                                            <a href="{{ route('add.customer') }}" class="btn btn-sm btn-outline-light">
                                                <i class="mdi mdi-plus"></i> New Customer
                                            </a>
                                        </div>
                                        <select name="customer_id" class="form-select" required>
                                            <option value="" selected disabled>Select Customer</option>
                                            @foreach($customer as $cus)
                                                <option value="{{ $cus->id }}">{{ $cus->name }} ({{ $cus->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Credit Card</option>
                                            <option value="mobile">Mobile Payment</option>
                                            <option value="bank">Bank Transfer</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" 
                                                  placeholder="Any special instructions..."></textarea>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="mdi mdi-cash-register me-2"></i> Complete Sale
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Selection Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-package-variant-closed me-2"></i>Product Catalog
                        </h5>
                        
                        <!-- Search & Filter Section -->
                        <div class="row mb-3 g-2">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                                    <input type="text" id="productSearch" class="form-control" 
                                           placeholder="Search products...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="groupFilter" class="form-select">
                                    <option value="">All Groups</option>
                                    @foreach($group as $grp)
                                        <option value="{{ $grp->id }}">{{ $grp->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="productTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Image</th>
                                        <th width="30%">Name</th>
                                        <th width="20%">Price</th>
                                        <th width="20%">Stock</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product as $key => $item)
                                    <tr data-category="{{ $item->category_id }}" 
                                        data-group="{{ $item->group_id }}" 
                                        data-name="{{ strtolower($item->product_name) }}">
                                        <form method="post" action="{{ url('/add-cart') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                           <input type="number" name="qty" value="1" min="1" hidden>
                                            
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                <img src="{{ asset($item->product_image) }}" 
                                                     class="img-thumbnail" 
                                                     style="width:50px; height:50px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $item->product_name }}</div>
                                                <small class="text-muted">
                                                    {{ $item->category->category_name ?? 'N/A' }} / 
                                                    {{ $item->group->group_name ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td class="fw-bold text-success">
                                                ${{ number_format($item->getPrice() / 100, 2) }}
                                            </td>
                                            <td>
                                                @if($item->product_store > 10)
                                                    <span class="badge bg-success">In Stock</span>
                                                @elseif($item->product_store > 0)
                                                    <span class="badge bg-warning">Low Stock</span>
                                                @else
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="submit" 
                                                        class="btn btn-primary btn-sm" 
                                                        @if($item->product_store <= 0) disabled @endif>
                                                    <i class="mdi mdi-plus"></i> Add
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-outline-secondary" onclick="printReceipt()">
                                <i class="mdi mdi-printer me-1"></i> Print Last Receipt
                            </button>
                            <button class="btn btn-outline-primary" onclick="holdCart()">
                                <i class="mdi mdi-content-save me-1"></i> Hold Cart
                            </button>
                            <button class="btn btn-outline-info" onclick="loadHoldCart()">
                                <i class="mdi mdi-folder-download me-1"></i> Load Held Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Update date/time every second
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            $('#currentDateTime').text(now.toLocaleDateString('en-US', options));
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Filter products
        $('#categoryFilter, #groupFilter, #productSearch').on('change keyup', function() {
            let category = $('#categoryFilter').val();
            let group = $('#groupFilter').val();
            let search = $('#productSearch').val().toLowerCase();
            
            $('#productTable tbody tr').each(function() {
                let productCategory = $(this).data('category');
                let productGroup = $(this).data('group');
                let productName = $(this).data('name');
                
                let categoryMatch = (category === '' || category == productCategory);
                let groupMatch = (group === '' || group == productGroup);
                let searchMatch = (search === '' || productName.includes(search));
                
                if (categoryMatch && groupMatch && searchMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    
    function clearCart() {
        if (confirm('Are you sure you want to clear the cart?')) {
            window.location.href = "{{ url('/cart-destroy') }}";
        }
    }
    
    function printReceipt() {
        window.open("{{ route('print.receipt') }}", '_blank');
    }
</script>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        font-weight: 600;
    }
    .table th {
        white-space: nowrap;
    }
    .form-control, .form-select {
        border-radius: 5px;
    }
    .badge {
        font-weight: 500;
    }
    .img-thumbnail {
        padding: 2px;
        border: 1px solid #dee2e6;
    }
</style>
@endsection