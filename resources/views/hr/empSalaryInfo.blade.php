          @extends('admin.masterManager')
          @section('content')
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    STAFF
                                    <i class="fa fa-circle"></i>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"></h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <!-- BEGIN DASHBOARD STATS 1--> 
     <?php if(Session::get('succes') != null) { ?>
   <div class="alert alert-info alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong><?php echo Session::get('succes') ;  ?></strong>
  <?php Session::put('succes',null) ;  ?>
</div>
<?php } ?>
<?php
if(Session::get('failed') != null) { ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
  <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
 <strong><?php echo Session::get('failed') ; ?></strong>
 <?php echo Session::put('failed',null) ; ?>
</div>
<?php } ?>

  @if (count($errors) > 0)
    @foreach ($errors->all() as $error)      
 <div class="alert alert-danger alert-dismissible" role="alert">
   <a href="#" class="fa fa-times" data-dismiss="alert" aria-label="close"></a>
  <strong>{{ $error }}</strong>
</div>
@endforeach
@endif
<div class="row">
 
                            <div class="col-md-12">
                                 
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject bold uppercase">Emp  Salary</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row" style="font-weight: bolder;">
                                            <div class="col-md-2"> Staff Name</div>
                                             <div class="col-md-1">:</div>
                                              <div class="col-md-9"><?php echo $row->name ; ?></div>
                                                <div class="col-md-2"> Designation</div>
                                             <div class="col-md-1">:</div>
                                              <div class="col-md-9"><?php echo $row->desi_name ; ?></div>
                                             </div>
                                              <div class="col-md-12">----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>
                                  
                                        </div>
                                        <br/>
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Created Date</th>
                                                    <th>Start Year</th>
                                                    <th>Start Month</th>
                                                    <th>Salary Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo $value->created_at ; ?></td>
                                                    <td><?php echo $value->year ; ?></td>
                                                    <td><?php
                                                    if($value->month == '01'){
                                                      echo "January";
                                                    }elseif($value->month == '02'){
                                                      echo "February";

                                                    }elseif($value->month == '03'){
                                                      echo "March";
                                                      
                                                    }elseif($value->month == '04'){
                                                      echo "Aprill";
                                                    }elseif($value->month == '05'){
                                                      echo "May";
                                                    }elseif($value->month == '06'){
                                                      echo "June";
                                                      
                                                    }elseif($value->month == '07'){
                                                      echo "July";
                                                      
                                                    }elseif($value->month == '08'){
                                                      echo "August";
                                                      
                                                    }elseif($value->month == '09'){
                                                      echo "September";
                                                      
                                                    }elseif($value->month == '10'){
                                                      echo "October";
                                                      
                                                    }elseif($value->month == '11'){
                                                      echo "November";
                                                      
                                                    }else{
                                                      echo "December";

                                                    }
                                                     ?></td>
                                                    <td><?php echo $value->salary_amount ; ?></td>
                                                   
                                                </tr>
                                                <?php } ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection