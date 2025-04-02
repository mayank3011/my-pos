@extends('admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a class="btn btn-primary rounded-pill waves-effect waves-light" href="{{ route('all.product') }}">Back</a>
                            </li>
                        </ol>
                    </div>
                    <h4 class="page-title">Bar Code Product</h4>
                </div>
            </div>
        </div>     
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-pane" id="settings">
                            <h5 class="mb-4 text-uppercase">
                                <i class="mdi mdi-account-circle me-1"></i> Bar Code Product
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product Name</label>
                                        <h3>{{ $product->product_name }}</h3>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Product Code</label>
                                        <h3>{{ $product->product_code }}</h3>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Barcode</label>
                                        <div class="barcode-container p-3 border rounded">
                                            {!! $barcode !!}
                                            <p class="mt-2 mb-0">{{ $product->product_code }}</p>
                                        </div>
                                        <button onclick="window.print()" class="btn btn-primary mt-3">
                                            <i class="fas fa-print"></i> Print Barcode
                                        </button>
                                    </div>
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
    .barcode-container svg {
        max-width: 100%;
        height: auto;
    }
    @media print {
        .page-title-box, .breadcrumb, button {
            display: none !important;
        }
        .barcode-container {
            border: none !important;
            padding: 0 !important;
        }
    }
</style>

@endsection