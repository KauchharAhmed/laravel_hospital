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
                                 OPD SETTING
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
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                          OPD SETTING
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' =>'opdSettingInfo','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                                            <div class="form-body">
                                              <span style="color:green">All Amount Get Doctor =</span><br/> Not Calcaulation With Whole Hospital Account. Only Track Doctor OPD History
                                              <br/>
                                              <span style="color:green">All Amount Get Doctor =</span><br/>
                                              Calcaulation With Whole Hospital Account
                                              <br/>
                                               <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                      CURRENT STATUS = 
                                                      <?php if($count == '0'):?>
                                                        <span style="color:red;">NOT INSERT ANY STATUS</span>
                                                      <?php else:?>
                                                        <span style="color: blue;">
                                                          <?php if($result->current_status == '1'){
                                                            echo "All Amount Get Doctor";
                                                          }else{
                                                           echo "Hospital Get Some Amount";
                                                          }
                                                          ?>
                                                        </span>
                                                      <?php endif;?>  
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label"> Change Status  <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select  class="form-control spinner" name="opd_status" required="">
                                                          <?php if($count == '0'):?>
                                                          <option value="">Select Status</option>
                                                          <option value="1">All Amount Get Doctor</option>
                                                          <option value="2">Hospital Get Some Amount</option>
                                                        <?php else:?>
                                                          <option value="<?php echo $result->current_status;?>">
                                                            <?php if($result->current_status == '1'){
                                                                echo "All Amount Get Doctor";
                                                            }else{
                                                                echo "Hospital Get Some Amount";
                                                            }
                                                            ?>
                                                          </option>
                                                           <option value="1">All Amount Get Doctor</option>
                                                          <option value="2">Hospital Get Some Amount</option>
                                                        <?php endif;?>
                                                        </select>
                                                </div>
                                              </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="submit" class="btn green">Submit</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->             
            </div><!-- END CONTAINER -->
@endsection
