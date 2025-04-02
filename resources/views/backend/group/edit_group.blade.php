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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Group</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Edit Group</h4>
                </div>
            </div>
        </div>     
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="tab-pane" id="settings">
                            <form method="post" action="{{ route('group.update') }}">
                                @csrf

                                <input type="hidden" name="id" value="{{ $group->id }}">

                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Edit Group</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Group Name</label>
                                            <input type="text" name="group_name" class="form-control" value="{{ $group->group_name }}">
                                        </div>
                                    </div>
                                </div> <!-- end row --> 

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2">
                                        <i class="mdi mdi-content-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end settings content-->

                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div> <!-- end row-->

    </div> <!-- container -->

</div> <!-- content -->

@endsection