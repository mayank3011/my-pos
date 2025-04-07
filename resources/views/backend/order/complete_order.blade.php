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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Complete Orders</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Complete Orders</h4>
                </div>
            </div>
        </div>     
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Order Date</th>
                                        <th>Payment</th>
                                        <th>Invoice</th>
                                        <th>Pay</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $key => $item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                @if($item->customer && $item->customer->image)
                                                    <img src="{{ asset($item->customer->image) }}" style="width:50px; height:40px;" class="img-thumbnail">
                                                @else
                                                    <div class="text-muted" style="width:50px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                        No Image
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $item->order_date }}</td>
                                            <td>{{ $item->payment_status }}</td>
                                            <td>{{ $item->invoice_no }}</td>
                                            <td>₹{{ number_format(floatval($item->pay), 2) }}</td>
                                            <td><span class="badge bg-success">{{ $item->order_status }}</span></td>
                                            <td>
                                                <a href="{{ url('order/invoice-download/'.$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light">
                                                    PDF Invoice
                                                </a>
                                            </td>
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

<style>
    .img-thumbnail {
        object-fit: cover;
    }
    .text-muted {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>

@endsection