@extends('admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title">Paid Salary</h4>
                    <nav>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Paid Salary</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>     
        <!-- End Page Title -->

        <div class="row">
            <div class="col-lg-8 col-xl-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <form method="post" action="{{ route('employe.salary.store') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $paysalary->id }}">

                            <h5 class="mb-4 text-uppercase fw-bold">
                                <i class="mdi mdi-cash-multiple me-1"></i> Salary Details
                            </h5>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Employee Name</label>
                                        <p class="form-control bg-light">{{ $paysalary->name }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Salary Month</label>
                                        <p class="form-control bg-light">{{ date("F", strtotime('-1 month')) }}</p>
                                        <input type="hidden" name="month" value="{{ date('F', strtotime('-1 month')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Employee Salary</label>
                                        <p class="form-control bg-light">
                                            <span class="badge bg-success">₹{{ number_format($paysalary->salary, 2) }}</span>
                                        </p>
                                        <input type="hidden" name="paid_amount" value="{{ $paysalary->salary }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Advance Salary</label>
                                        <p class="form-control bg-light">
                                            <span class="badge bg-warning">
                                                ₹{{ number_format(optional($paysalary['advance'])->advance_salary ?? 0, 2) }}
                                            </span>
                                        </p>
                                        <input type="hidden" name="advance_salary" value="{{ optional($paysalary['advance'])->advance_salary ?? 0 }}">
                                    </div>
                                </div>

                                @php
                                    $advanceSalary = optional($paysalary['advance'])->advance_salary ?? 0;
                                    $amount = $paysalary->salary - $advanceSalary;
                                @endphp

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Due Salary</label>
                                        <p class="form-control bg-light">
                                            @if($advanceSalary == 0)
                                                <span class="badge bg-secondary">No Advance</span>
                                            @else
                                                <span class="badge bg-danger">₹{{ number_format($amount, 2) }}</span>
                                            @endif
                                        </p>
                                        <input type="hidden" name="due_salary" value="{{ $amount }}">
                                    </div>
                                </div>

                            </div> <!-- End Row -->

                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill waves-effect waves-light mt-3">
                                    <i class="mdi mdi-check-circle-outline"></i> Pay Salary
                                </button>
                            </div>

                        </form>
                    </div>
                </div> <!-- End Card -->
            </div> <!-- End Col -->
        </div> <!-- End Row -->

    </div> <!-- End Container -->
</div> <!-- End Content -->

@endsection
