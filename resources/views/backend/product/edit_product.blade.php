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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Product</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Product</h4>
                </div>
            </div>
        </div>     
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="tab-pane" id="settings">
                            <form id="myForm" method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Edit Product</h5>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="product_name" class="form-label">Product Name</label>
                                            <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select name="category_id" class="form-select" id="example-select">
                                                <option selected disabled>Select Category</option>
                                                @foreach($category as $cat)
                                                    <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="group_id" class="form-label">Group</label>
                                            <select name="group_id" class="form-select" id="example-select">
                                                <option selected disabled>Select Category</option>
                                                @foreach($group as $grp)
                                                    <option value="{{ $grp->id }}" {{ $grp->id == $product->group_id ? 'selected' : '' }}>{{ $grp->group_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="supplier_id" class="form-label">Supplier</label>
                                            <select name="supplier_id" class="form-select" id="example-select">
                                                <option selected disabled>Select Supplier</option>
                                                @foreach($supplier as $sup)
                                                    <option value="{{ $sup->id }}" {{ $sup->id == $product->supplier_id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="product_code" class="form-label">Product Code</label>
                                            <input type="text" name="product_code" class="form-control" value="{{ $product->product_code }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="product_garage" class="form-label">Product Garage</label>
                                            <input type="text" name="product_garage" class="form-control" value="{{ $product->product_garage }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="product_store" class="form-label">Product Store</label>
                                            <input type="text" name="product_store" class="form-control" value="{{ $product->product_store }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="buying_date" class="form-label">Buying Date</label>
                                            <input type="date" name="buying_date" class="form-control" value="{{ $product->buying_date }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="expire_date" class="form-label">Expire Date</label>
                                            <input type="date" name="expire_date" class="form-control" value="{{ $product->expire_date }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="buying_price" class="form-label">Buying Price</label>
                                            <input type="text" name="buying_price" class="form-control" value="{{ $product->buying_price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="selling_price" class="form-label">Selling Price</label>
                                            <input type="text" name="selling_price" class="form-control" value="{{ $product->selling_price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="product_image" class="form-label">Product Image</label>
                                            <input type="file" name="product_image" id="image" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <img id="showImage" src="{{ asset($product->product_image) }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                        </div>
                                    </div>

                                </div> <!-- end row -->

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2">
                                        <i class="mdi mdi-content-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div> <!-- end row-->
    </div> <!-- container -->
</div> <!-- content -->

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                product_name: { required: true }, 
                category_id: { required: true }, 
                supplier_id: { required: true }, 
                product_code: { required: true }, 
                product_garage: { required: true }, 
                product_store: { required: true }, 
                buying_date: { required: true }, 
                expire_date: { required: true }, 
                buying_price: { required: true }, 
                selling_price: { required: true }, 
            },
            messages: {
                product_name: { required: 'Please Enter Product Name' },
                category_id: { required: 'Please Select Category' },
                supplier_id: { required: 'Please Select Supplier' },
                product_code: { required: 'Please Enter Product Code' },
                product_garage: { required: 'Please Enter Product Garage' },
                product_store: { required: 'Please Enter Product Store' },
                buying_date: { required: 'Please Select Buying Date' },
                expire_date: { required: 'Please Select Expire Date' },
                buying_price: { required: 'Please Enter Buying Price' },
                selling_price: { required: 'Please Enter Selling Price' },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); }
        });
    });

    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){ $('#showImage').attr('src',e.target.result); }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>

@endsection