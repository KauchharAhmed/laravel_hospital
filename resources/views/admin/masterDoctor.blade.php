<?php
    $admin_id       = Session::get('admin_id');
    $type           = Session::get('type');
 
    if($admin_id == null && $type == null){
        return Redirect::to('/')->send();
        exit();
    }

    if($admin_id == null && $type != '4'){
        return Redirect::to('/')->send();
        exit();
    }

    if($type != '4'){
        return Redirect::to('/')->send();
        exit();
    }
?>
<!DOCTYPE html>

<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>HOSPITAL</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{URL::to('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css')}}" rel="stylesheet" />
        <link href="{{URL::to('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <link href="{{URL::to('public/assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{URL::to('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
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
        <link href="{{URL::to('public/assets/layouts/layout/css/personal.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::to('public/assets/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
       
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
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <span style="color:white; font-weight: bold;">Doctor Panel</span>
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
                            <!-- BEGIN SALES NOTIFICATION -->
                            <!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
                            <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                            <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                             <li style="padding-top: 11px;padding-right: 8px;">
                                    <span style="color:white;">
                                    </span>

                            </li>
                            <!-- END SALES NOTIFICATION -->

                            <!-- BEGIN BANK TRANSANCTION NOTIFICATION -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                       
                            <!-- END BANK TRANSANCTION NOTIFICATION -->

                            <!-- BEGIN COLLECTION NOTIFICATION -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                               
                                <ul class="dropdown-menu">
                                    <li class="external">

                                </ul>
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

                                    <span class="username username-hide-on-mobile"><?php echo Session::get('admin_name'); ?>  </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <!--<li>
                                        <a href="page_user_profile_1.html">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>-->
                                    <li>
                                        <a href="{{URL::to('adminChangePassword')}}">
                                            <i class="icon-calendar"></i> Change Password </a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('adminLogout')}}">
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
                    <div class="page-sidebar navbar-collapse collapse">
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
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                            <li class="nav-item start">
                                <a href="{{URL::to('doctorDashboard')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Dashboard</span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <li class="heading">
                                <h3 class="uppercase">MAIN MENU</h3>
                            </li>

                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">MEDICINE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addMedicineType')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Medicine Type </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageMedicineType')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Medicine Type</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addMedicine')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Medicine</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageMedicine')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Medicine</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">UNAIDED</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addUnaidedVar')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add VAR</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageUnaidedVar')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage VAR</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addUnaidedVal')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add VAL</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageUnaidedVal')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage VAL</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">PINHOLE / SPECTACLE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addPinholeVar')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add VAR </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePinholeVar')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage VAR</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('addPinholeVal')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add VAL</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePinholeVal')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage VAL</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">ANTERIOR SEGMENT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addAnteriorSegment')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Segment </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageAnteriorSegment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Segment</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">POSTEROR SEGMENT</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addPosterorSegment')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Segment </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('managePosterorSegment')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Segment</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">DISGNOSIS</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addDisgnosis')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Diagnosis </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageDisgnosis')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Segment</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">ADVICE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addAdvice')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Advice </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageAdvice')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Advice</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">FOLLOW-UP</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addFollowup')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Follow-up </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageFollowup')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Follow-up</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-diamond"></i>
                                    <span class="title">DOSE</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{URL::to('addDose')}}" class="nav-link">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Add Dose </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{URL::to('manageDose')}}" class="nav-link ">
                                            <i class="icon-diamond"></i>
                                            <span class="title">Manage Dose </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
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
                <a href="javascript:;" class="page-quick-sidebar-toggler">
                    <i class="icon-login"></i>
                </a>
                
                    </div>
                </div>
                <!-- END QUICK SIDEBAR -->
                   @yield ('content')

            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> <?php echo date('Y') ;?> &copy; Point Of Sale
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
        <script src="{{URL::to('public/assets/global/plugins/horizontal-timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('public/assets/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
  
        <script src="{{URL::to('public/assets/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
    
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
