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
                                   ROOM
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
                                            <span class="caption-subject bold uppercase">cabin room list</span>
                                        </div>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Building Name</th>
                                                    <th>Floor</th>
                                                    <th>Cabin Type</th>
                                                    <th>Room No</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <!--<th>Edit</th>
                                                    <th>Delete</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { ?>
                                                <tr>
                                                    <td><?php echo $i++ ;?></td>
                                                    <td><?php echo $value->building_name ; ?></td>
                                                    <td><?php echo $value->floor_name ; ?></td>
                                                    <td><?php echo $value->cabin_type_name ; ?></td>
                                                    <td><?php echo $value->room_no ; ?></td>
                                                     <td><?php if($value->status == '0'):?>
                                                       <span style="color:green;font-weight: bold">ACTIVE</span>
                                                     <?php else:?>
                                                    <span style="color:red;font-weight: bold">INACTIVE</span>
                                                     <?php endif;?> 

                                                      </td>
                                                    <td><?php echo $value->remarks ; ?></td>
                                                    <!--<td>
                                                        <button type="button" class="btn btn-info btn-sm">EDIT</button>
                                                    </td>
                                                       <td>
                                                        <button type="button" class="btn btn-danger btn-sm">DELETE</button>
                                                    </td>-->
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