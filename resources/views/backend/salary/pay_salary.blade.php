@extends('admin_dashboard')

@section('admin')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title">All Pay Salary</h4>
                        <a href="{{ route('add.advance.salary') }}" class="btn btn-primary rounded-pill waves-effect waves-light">
                            <i class="fas fa-plus-circle"></i> Add Advance Salary
                        </a>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h4 class="header-title text-center text-primary">{{ date('F Y') }}</h4>

                            <table id="basic-datatable" class="table table-striped table-bordered nowrap w-100">
                                <thead class="table-dark">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Month</th>
                                        <th>Salary</th>
                                        <th>Advance</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($employee as $key => $item)
                                        <tr class="text-center align-middle">
                                            <td>{{ $key + 1 }}</td>
                                            
                                            <td>
                                                <img src="{{ asset($item->image ?? 'default.png') }}" 
                                                     class="rounded-circle border shadow" 
                                                     style="width:50px; height:40px;" 
                                                     alt="Employee Image">
                                            </td>
                                            
                                            <td class="fw-bold">{{ $item->name }}</td>
                                            
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ date('F', strtotime('-1 month')) }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-success px-3 py-2">
                                                    ₹{{ number_format($item->salary, 2) }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                @if(optional($item->advance)->advance_salary)
                                                    <span class="badge bg-warning px-3 py-2">
                                                        ₹{{ number_format($item->advance->advance_salary, 2) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2">No Advance</span>
                                                @endif
                                            </td>
                                            
                                            <td>
                                                @php
                                                    $amount = $item->salary - optional($item->advance)->advance_salary;
                                                @endphp
                                                <span class="badge bg-danger px-3 py-2">
                                                    ₹{{ number_format($amount, 2) }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <a href="{{ route('pay.now.salary', $item->id) }}" 
                                                   class="btn btn-blue rounded-pill waves-effect waves-light">
                                                   <i class="far fa-rupee-sign"></i> Pay Now
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->
    </div> <!-- content -->
@endsection
