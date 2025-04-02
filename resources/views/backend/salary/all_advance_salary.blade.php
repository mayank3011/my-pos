@extends('admin_dashboard')

@section('admin')
    <div class="content">
        <div class="container-fluid">
            
            <!-- Start Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <h4 class="page-title">All Advance Salary</h4>
                        <a href="{{ route('add.advance.salary') }}" class="btn btn-primary rounded-pill waves-effect waves-light">
                            <i class="fas fa-plus-circle"></i> Add Advance Salary
                        </a>
                    </div>
                </div>
            </div>     
            <!-- End Page Title --> 

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            
                            <table id="basic-datatable" class="table table-striped table-bordered nowrap w-100">
                                <thead class="table-dark">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Month</th>
                                        <th>Salary</th>
                                        <th>Advance Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach($salary as $key => $item)
                                        <tr class="text-center align-middle">
                                            <td>{{ $key + 1 }}</td>
                                            
                                            <td>
                                                @if(optional($item->employee)->image)
                                                    <img src="{{ asset($item->employee->image) }}" 
                                                         class="rounded-circle border shadow" 
                                                         style="width:50px; height:40px;" 
                                                         alt="Employee Image">
                                                @else
                                                    <span class="badge bg-secondary">No Image</span>
                                                @endif
                                            </td>
                                            
                                            <td class="fw-bold">{{ optional($item->employee)->name ?? 'Unknown Employee' }}</td>
                                            
                                            <td>
                                                <span class="badge bg-info">{{ $item->month }}</span>
                                            </td>
                                            
                                            <td>
                                                <span class="badge bg-success px-3 py-2">
                                                    ₹{{ number_format(optional($item->employee)->salary ?? 0, 2) }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                @if($item->advance_salary)
                                                    <span class="badge bg-warning px-3 py-2">
                                                        ₹{{ number_format($item->advance_salary, 2) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2">No Advance</span>
                                                @endif
                                            </td>
                                            
                                            <td>
                                                <a href="{{ route('edit.advance.salary', $item->id) }}" 
                                                   class="btn btn-blue rounded-pill waves-effect waves-light">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <a href="{{ route('delete.advance.salary', $item->id) }}" 
                                                   class="btn btn-danger rounded-pill waves-effect waves-light" 
                                                   id="delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> <!-- End Card Body -->
                    </div> <!-- End Card -->
                </div><!-- End Col -->
            </div>
            <!-- End Row -->

        </div> <!-- End Container -->
    </div> <!-- End Content -->
@endsection
