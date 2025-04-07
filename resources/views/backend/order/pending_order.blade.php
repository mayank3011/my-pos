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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Orders</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Pending Orders</h4>
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
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Order Date</th>
                                        <th>Payment</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $key => $item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                @if($item->customer && $item->customer->image)
                                                    <img src="{{ asset($item->customer->image) }}" class="rounded-circle" style="width:50px; height: 40px;" alt="customer-image">
                                                @else
                                                    <div class="avatar-sm text-muted">
                                                        <span class="avatar-title bg-soft-secondary rounded">No Image</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $item->order_date }}</td>
                                            <td><span class="badge bg-soft-info text-info">{{ $item->payment_status }}</span></td>
                                            <td>{{ $item->invoice_no }}</td>
                                            <td>₹{{ number_format($item->pay, 2) }}</td>
                                            <td><span class="badge bg-danger">{{ $item->order_status }}</span></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('order.details',$item->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </a>
                                                    <a href="{{ route('order.sendMail', $item->id) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Send Email">
                                                        <i class="mdi mdi-email-outline"></i>
                                                    </a>
                                                    <a href="{{ route('order.print', $item->id) }}" target="_blank" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Print Invoice">
                                                        <i class="mdi mdi-printer-outline"></i>
                                                    </a>
                                                </div>
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

<!-- Add Tooltip Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@endsection