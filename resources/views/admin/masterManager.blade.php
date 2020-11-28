<?php
$admin_id       = Session::get('admin_id');
$type           = Session::get('type');
$branch_id      = Session::get('branch_id');
       
       if($admin_id == null && $type == null){
       return Redirect::to('/admin')->send();
       exit();
        }

       if($admin_id == null && $type != '2'){
       return Redirect::to('/admin')->send();
       exit();
        }
        
        if($type != '2'){
       return Redirect::to('/admin')->send();
       exit();
        }
       if($branch_id == ''){
       return Redirect::to('/admin')->send();
       exit();
        }
        ?>
<!DOCTYPE html>

<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>HOSPITAL MANAGEMENT</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{URL::to('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css')}}" rel="stylesheet" />
        <!--<link href="{{URL::to('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css')}}" />-->
        <link href="{{URL::to('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <link href="{{URL::to('public/assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />

        <!--<link href="{{URL::to('public/assets/global/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />-->
        <!--<link href="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />-->
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{URL::to('public/assets/global/css/components-rounded.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{URL::to('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{URL::to('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/layouts/layout/css/themes/darkblue.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{URL::to('public/assets/layouts/layout/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/clockface/css/clockface.css')}}" rel="stylesheet" type="text/css" />
         <link href="{{URL::to('public/assets/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
         <link href="{{URL::to('public/assets/layouts/layout/css/personal.css')}}" rel="stylesheet" type="text/css" />


        <!-- END THEME LAYOUT STYLES -->
        <!--<link rel="shortcut icon" href="favicon.ico" />-->
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>


    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top" style="background: #4267b2;">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                
                          <span style="color:white; font-weight: bold">
                              <?php
                               // branch name
                              $query = DB::table('branch')->where('id',$branch_id)->first();
                              echo $query->name ;
                              ?>
                          </span>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            
                              <li style="padding-top: 11px;padding-right: 8px;">
                                    <span style="color:white;">Cash :<?php $cash = DB::table('pettycash')->where('branch_id',$branch_id)->where('type',2)->first();
                                       echo $cash->pettycash_amount ;
                                    ?>
                                    </span>

                            </li>
                            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                             <a href="{{URL::to('cashierBalanceTransferReceiveByManager')}}" class="dropdown-toggle">
                                   
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-danger"> <?php
                                      $count_balance = DB::table('balance_transfer')->where('branch_id',$branch_id)->where('status',0)->count();
                                      echo $count_balance ;
                                    ?></span>
                                </a>
                            </li>
                            <!-- END COLLECTION NOTIFICATION -->

                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <?php if(Session::get('photo') != '' ):?>
                                      <img alt="" class="img-circle" src="<?php echo Session::get('photo') ; ?>" />
                                    <?php else:?>
                                     <img alt="" class="img-circle" src="public/assets/layouts/layout/img/avatar.jpg" />
                                    <?php endif;?>
                                    <span class="username username-hide-on-mobile"><?php echo Session::get('admin_name');?>  </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <!--<li>
                                        <a href="page_user_profile_1.html">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>-->
                                    <li>
                                    <a href="{{URL::to('managerChangePassword')}}">
                                            <i class="icon-calendar"></i> Change Password </a>
                                    </li>
                                    <!--<li>
                                        <a href="app_inbox.html">
                                            <i class="icon-envelope-open"></i> My Inbox
                                            <span class="badge badge-danger"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="app_todo.html">
                                            <i class="icon-rocket"></i> My Tasks
                                            <span class="badge badge-success"> 7 </span>
                                        </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="page_user_lock_1.html">
                                            <i class="icon-lock"></i> Lock Screen </a>
                                    </li>-->
                                    <li>
                                        <a href="{{URL::to('managerLogout')}}">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!--<li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <i class="icon-logout"></i>
                                </a>
                            </li>-->
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse" style="background: #1e3c50;">
                        <!-- BEGIN SIDEBAR MENU -->
                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200" style="padding-top: 20px">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <!--<li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>-->
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                            <li class="nav-item start">
                                <a href="{{URL::to('managerDashboard')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">DASHBOARD</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <?php
                             $cassier_pettycash_count = DB::table('pettycash')->where('branch_id',$branch_id)->where('type',3)->count() ;
                            ?>
                            <?php if($cassier_pettycash_count == '0') { ?>
                            <li class="nav-item start">
                                <a href="{{URL::to('managerAddOpeningCashierBalance')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Add Cashiers Pettycash</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <?php } ?>
                           <?php if($cassier_pettycash_count > '0') { ?>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">UNIT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                      <li class="nav-item">
                                        <a href="{{URL::to('addUnit')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Unit</span>
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{URL::to('manageUnit')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Unit</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">MEDICAL PRODUCTS</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addProduct')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Products</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageProduct')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Product</span>
                                        </a>
                                    </li> 
                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">PATHOLOGY TEST</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addMedicalTest')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Test</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageMedicalTest')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Test</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                               <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">HOSPITAL BUILDING</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addBuilding')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Building </span>
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{URL::to('manageBuilding')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Building </span>
                                        </a>
                                    </li> 
                                      <li class="nav-item">
                                        <a href="{{URL::to('addBuildingFloor')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Building Floor </span>
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                        <a href="{{URL::to('manageBuildingFloor')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Building Floor </span>
                                        </a>
                                    </li> 
                                </ul>
                            </li>

                               <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">CABIN</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addCabinType')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Cabin Type </span>
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                        <a href="{{URL::to('manageCabinType')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Cabin Type </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addCabinRoom')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Cabin Room </span>
                                        </a>
                                    </li> 
                                      <li class="nav-item">
                                        <a href="{{URL::to('manageCabinRoom')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Cabin Room </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                              <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">WARD</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addWard')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Ward</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageWard')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Ward</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">CASHIER</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addCashier')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Cashier</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageCashier')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Cashier</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">SUPPLIER</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addSupplier')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Supplier</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageCashier')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Supplier</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">PURCHASE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addPurchase')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Make Purchase</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePurchase')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Purchase</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                                 <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">PAYMENT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                     <li class="nav-item">
                                        <a href="{{URL::to('supplierPayment')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Supplier Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePayment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Supplier Payment</span>
                                        </a>
                                    </li>

                                       <li class="nav-item">
                                        <a href="{{URL::to('pcPayment')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">PC Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePayment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage PC Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('otPayment')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">OT Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageOtPayment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manag OT Payment</span>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">EXPENSE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                        <li class="nav-item">
                                        <a href="{{URL::to('addExpenseCategory')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Expense Category</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageExpenseCategory')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Expense Category</span>
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{URL::to('addManagerExpense')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Make Expense</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePayment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Expense</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                               <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">OPD</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                     <li class="nav-item">
                                        <a href="{{URL::to('opdSetting')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">OPD Setting</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addOpdCategory')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Opd Category</span>
                                        </a>
                                    </li>
                                      <li class="nav-item">
                                        <a href="{{URL::to('manageOpdCategory')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Opd Category</span>
                                        </a>
                                    </li>
                                       <li class="nav-item">
                                        <a href="{{URL::to('addOpdFee')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Opd Fee</span>
                                        </a>
                                    </li>
                                      <li class="nav-item">
                                        <a href="{{URL::to('manageOpdFee')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Opd Fee</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                             <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">IPD</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                     <li class="nav-item">
                                        <a href="{{URL::to('addIPDAdmissinFeeAmount')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add IPD Admission Fee</span>
                                        </a>
                                    </li>
                                       <li class="nav-item">
                                        <a href="{{URL::to('manageIPDAdmissinFeeAmount')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage IPD Admission Fee</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addIPDService')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add IPD Service</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageIPDService')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage IPD Service</span>
                                        </a>
                                    </li>
                                      </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">OT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                       <li class="nav-item">
                                        <a href="{{URL::to('addOTRoom')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add OT Room</span>
                                        </a>
                                    </li>

                                       <li class="nav-item">
                                        <a href="{{URL::to('manageOTRoom')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage OT Room</span>
                                        </a>
                                    </li>


                                     <li class="nav-item">
                                        <a href="{{URL::to('addOTtype')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add OT Type</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageOTtype')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage OT Type</span>
                                        </a>
                                    </li>
                                            <li class="nav-item">
                                        <a href="{{URL::to('addOTService')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add OT Service</span>
                                        </a>
                                    </li>
                                           <li class="nav-item">
                                        <a href="{{URL::to('manageOTService')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage OT Service</span>
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                        <a href="{{URL::to('OTamountDistribution')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title"> OT Amount Distribution</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                              <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">BANK</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addBank')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Bank</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageBank')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Bank</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                              <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">HR</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addDesignation')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Designation</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageDesignation')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Designation</span>
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                        <a href="{{URL::to('addEmp')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Staff</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageEmp')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Staff</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">SALARY</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageSalary')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Incress / Decress Salary</span>
                                        </a>
                                    </li>
                                
                                    <li class="nav-item">
                                        <a href="{{URL::to('salaryPaymentForm')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Salary Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('salaryManage')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Salary</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                               <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title"> TRANSACTION </span>
                                    <span class="arrow"></span>
                                </a>
                                <?php
                                 $count = DB::table('balance_transfer')->where('branch_id',$branch_id)->where('status',0)->count();
                                ?>
                                <ul class="sub-menu">
                                     <li class="nav-item">
                                        <a href="{{URL::to('cashierBalanceTransferReceiveByManager')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Receive Cash <span style="color:red;">(<?php echo $count ;?>)</span></span>
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{URL::to('adminManageReceiveCash')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Receive Cash</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('cashToBankTransaction')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Cash to Bank</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageCashToBank')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Cash to Bank</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('bankToCashTransaction')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Bank to Cash</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageBankToCash')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Bank to Cash</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">LEDGER</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                       <li class="nav-item">
                                        <a href="{{URL::to('supplierLedger')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Supplier Ledger</span>
                                        </a>
                                    </li>
                                        <li class="nav-item">
                                        <a href="{{URL::to('pcLedger')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">PC Ledger</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                 <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">CASH & BANK BOOK</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                       <li class="nav-item">
                                        <a href="{{URL::to('managerFullCashbook')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Full Cashbbok</span>
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{URL::to('managerTodayCashbook')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Today Cashbbok</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managerDatewiseCashbook')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Date Wise Cashbook</span>
                                        </a>
                                    </li>   
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">REPORT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                        <li class="nav-item">
                                        <a href="{{URL::to('managerIncomeReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Income Report</span>
                                        </a>
                                    </li>
                                       <li class="nav-item">
                                        <a href="{{URL::to('managerPurchaseReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Purchase Report</span>
                                        </a>
                                    </li>  
                                        <li class="nav-item">
                                        <a href="{{URL::to('managerSupplierPaymentReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Supplier Payment Report</span>
                                        </a>
                                    </li> 
                                    <li class="nav-item">
                                        <a href="{{URL::to('managerPCPaymentReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">PC Payment Report</span>
                                        </a>
                                    </li> 
                                        <li class="nav-item">
                                        <a href="{{URL::to('managerExpenseReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Expense Report</span>
                                        </a>
                                    </li>
                                      <li class="nav-item">
                                        <a href="{{URL::to('managerBankStatetmentReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Bank Statement</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managerCashToBankReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Cash To Bank Report</span>
                                        </a>
                                    </li>
                                      <li class="nav-item">
                                        <a href="{{URL::to('managerBankToCashReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Bank To Cash Report</span>
                                        </a>
                                    </li>
                                       <li class="nav-item">
                                        <a href="{{URL::to('managerCashReceiveReport')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Cash Receive Report</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                               <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">SETTING</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                       <li class="nav-item">
                                        <a href="{{URL::to('managerDeleteSetting')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Delete Setting</span>
                                        </a>
                                    </li>   
                                </ul>
                            </li>
                        <?php } ?>
                        </ul>
                        <!-- END SIDEBAR MENU -->
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                        
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->

                <!-- BEGIN QUICK SIDEBAR -->
                <!-- END QUICK SIDEBAR -->
                   @yield ('content')

            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> <?php echo date('Y') ;?> &copy; Hospital Management
                   &nbsp;|&nbsp;
                    <a href="http://asianitinc.com" target="_blank">Asian IT</a>
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!-- BEGIN QUICK NAV -->
        <div class="quick-nav-overlay"></div>
        <!-- END QUICK NAV -->
      
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{URL::to('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{URL::to('public/assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/morris/morris.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/amcharts.js')}}" type="text/javascript"></script>-->
        <!-- <script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/serial.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/pie.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/radar.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/themes/light.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/themes/patterns.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amcharts/themes/chalk.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/ammap/ammap.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/amcharts/amstockcharts/amstock.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>-->
        <script src="{{URL::to('public/assets/global/plugins/horizontal-timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
        <!--<script src="{{URL::to('public/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js')}}" type="text/javascript"></script>-->
        <script src="{{URL::to('public/assets/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
        <!--<script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js')}}" type="text/javascript"></script>-->
        <!--<script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- data table -->
        <script src="{{URL::to('public/assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{URL::to('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <script src="{{URL::to('public/assets/pages/scripts/table-datatables-responsive.min.js')}}" type="text/javascript"></script>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{URL::to('public/assets/pages/scripts/table-datatables-editable.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{URL::to('public/assets/pages/scripts/dashboard.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{URL::to('public/assets/pages/scripts/components-date-time-pickers.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/layouts/global/scripts/quick-nav.min.js')}}" type="text/javascript"></script>

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{URL::to('public/assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/clockface/js/clockface.js')}}" type="text/javascript"></script>
          <script src="{{URL::to('public/assets/bootstrap-select.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>
 @yield ('js')
