    <div class="left-side-menu">

        <div class="h-100" data-simplebar>

            <!-- User box -->


            <!--- Sidemenu -->
            <div id="sidebar-menu">

                <ul id="side-menu">

                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="{{ url('/dashboard') }}">
                            <i class="mdi mdi-view-dashboard-outline"></i>
                            <span> Dashboards </span>
                        </a>
                    </li>


                        <li>
                            <a href="{{ route('pos') }}">
                                <span class="badge bg-pink float-end">Hot</span>
                                <i class="mdi mdi-cash-register"></i>
                                <span> POS </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('analytics.index') }}">
                                <span class="badge bg-blue float-end">Yeah</span>
                                <i class="mdi mdi-chart-line"></i>
                                <span> Sales Report </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('return_orders.index') }}">
                                <span class="badge bg-blue float-end">Yeah</span>
                                <i class="mdi mdi-undo"></i>
                                <span>Return Orders</span>
                            </a>
                        </li>




                    <li class="menu-title mt-2">Apps</li>


                        <li>
                            <a href="#sidebarEcommerce" data-bs-toggle="collapse">
                                <i class="mdi mdi-account-tie"></i>
                                <span> Employee Manage </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('all.employee') }}">All Employee</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('add.employee') }}">Add Employee </a>
                                        </li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <a href="#sidebarCrm" data-bs-toggle="collapse">
                                <i class="mdi mdi-account-group"></i>
                                <span> Customer Manage </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarCrm">
                                <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('all.customer') }}">All Customer</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('add.customer') }}">Add Customer</a>
                                        </li>

                                </ul>
                            </div>
                        </li>
                    

                        <li>
                            <a href="#sidebarEmail" data-bs-toggle="collapse">
                                <i class="mdi mdi-truck"></i>
                                <span> Supplier Manage </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEmail">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.supplier') }}">All Supplier</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('add.supplier') }}">Add Supplier</a>
                                    </li>

                                </ul>
                            </div>
                        </li>

                        <li>
                            <a href="#salary" data-bs-toggle="collapse">
                                <i class="mdi mdi-cash-multiple"></i>
                                <span> Employee Salary </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="salary">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('add.advance.salary') }}">Add Advance Salary</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('all.advance.salary') }}">All Advance Salary</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('pay.salary') }}">Pay Salary</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('month.salary') }}">Last Month Salary</a>
                                    </li>

                                </ul>
                            </div>
                        </li>


                        <li>
                            <a href="#attendence" data-bs-toggle="collapse">
                                <i class="mdi mdi-calendar-check"></i>
                                <span> Employee Attendence </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="attendence">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('employee.attend.list') }}">Employee Attendence List </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                   
                        <li>
                            <a href="#category" data-bs-toggle="collapse">
                                <i class="mdi mdi-shape"></i>
                                <span> Category </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="category">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.category') }}">All Category </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#group" data-bs-toggle="collapse">
                                <i class="mdi mdi-folder-plus"></i>
                                <span>Add Class</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="group" id="group">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.group') }}">All Category </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#product" data-bs-toggle="collapse">
                                <i class="mdi mdi-package-variant"></i>
                                <span> Products </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="product">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.product') }}">All Product </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('add.product') }}">Add Product </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('import.product') }}">Import Product </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#orders" data-bs-toggle="collapse">
                                <i class="mdi mdi-cart"></i>
                                <span> Orders </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="orders">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('pending.order') }}">Pending Orders </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('complete.order') }}">Complete Orders </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('pending.due') }}">Pending Due </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#stock" data-bs-toggle="collapse">
                                <i class="mdi mdi-warehouse"></i>
                                <span> Stock Manage </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="stock">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('stock.manage') }}">Stock </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#permission" data-bs-toggle="collapse">
                                <i class="mdi mdi-lock"></i>
                                <span> Roles And Permission </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="permission">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.permission') }}">All Permission </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('all.roles') }}">All Roles </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('add.roles.permission') }}">Roles in Permission </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('all.roles.permission') }}">All Roles in Permission </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#admin" data-bs-toggle="collapse">
                                <i class="mdi mdi-shield-account"></i>
                                <span> Setting Admin User </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="admin">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('all.admin') }}">All Admin </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('add.admin') }}">Add Admin </a>
                                    </li>

                                </ul>
                            </div>
                        </li>




                    <li class="menu-title mt-2">Custom</li>

                        <li>
                            <a href="#sidebarAuth" data-bs-toggle="collapse">
                                <i class="mdi mdi-currency-inr"></i>
                                <span>Expense </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarAuth">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('add.expense') }}">Add Expense</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('today.expense') }}">Today Expense</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('month.expense') }}">Monthly Expense</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('year.expense') }}">Yearly Expense</a>
                                    </li>

                                </ul>
                            </div>
                        </li>

                    <li>
                        <a href="#backup" data-bs-toggle="collapse">
                            <i class="mdi mdi-cloud-sync"></i>
                            <span>Database Backup </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="backup">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('database.backup') }}">Database Backup </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
            </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

    </div>