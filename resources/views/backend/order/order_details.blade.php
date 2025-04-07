@extends('admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Order Details</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Order Details</h4>
                </div>
            </div>
        </div>     
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('order.status.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">

                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Order Details</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Image</label>
                                        @if($order->customer && $order->customer->image)
                                            <img src="{{ asset($order->customer->image) }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                        @else
                                            <div class="text-muted">No Image Available</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <p class="text-danger">{{ $order->customer->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Email</label>
                                        <p class="text-danger">{{ $order->customer->email ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Phone</label>
                                        <p class="text-danger">{{ $order->customer->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Order Date</label>
                                        <p class="text-danger">{{ $order->order_date }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Order Invoice</label>
                                        <p class="text-danger">{{ $order->invoice_no }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <p class="text-danger">{{ $order->payment_status }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Paid Amount</label>
                                        <p class="text-danger">₹{{ number_format(floatval($order->pay), 2) }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Due Amount</label>
                                        <p class="text-danger">₹{{ number_format(floatval($order->due), 2) }}</p>
                                    </div>
                                </div>

                                <!-- Discount Section -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount Applied</label>
                                        <p class="text-danger">
                                            @if(floatval($order->discount_amount) > 0)
                                                ₹{{ number_format(floatval($order->discount_amount), 2) }}
                                                @if($order->discount_type)
                                                    ({{ ucfirst($order->discount_type) }})
                                                @endif
                                            @else
                                                No Discount
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subtotal</label>
                                        <p class="text-danger">₹{{ number_format(floatval($order->sub_total), 2) }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tax (VAT)</label>
                                        <p class="text-danger">₹{{ number_format(floatval($order->vat), 2) }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Grand Total</label>
                                        <p class="text-danger fw-bold">₹{{ number_format(floatval($order->total), 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success waves-effect waves-light mt-2">
                                    <i class="mdi mdi-content-save"></i> Complete Order
                                </button>
                            </div>
                        </form>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Order Items</h5>
                                    <table class="table dt-responsive nowrap w-100">
                                        <thead>
                                            <tr> 
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Product Code</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orderItem as $item)
                                            <tr>
                                                <td>
                                                    @if($item->product && $item->product->product_image)
                                                        <img src="{{ asset($item->product->product_image) }}" style="width:50px; height:40px;">
                                                    @else
                                                        <div class="text-muted">No Image</div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                                                <td>{{ $item->product->product_code ?? 'N/A' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>₹{{ number_format(floatval($item->unitcost), 2) }}</td>
                                                <td>₹{{ number_format(floatval($item->total), 2) }}</td> 
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-danger {
        font-weight: 500;
    }
    .fw-bold {
        font-size: 1.1em;
    }
    .avatar-lg {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }
</style>

@endsection