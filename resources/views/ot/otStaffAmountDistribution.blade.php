          @extends('admin.masterManager')
          @section('content')
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar" style="background: aqua;color: black;font-weight: bold;">
                            <ul class="page-breadcrumb">
                                <li>
                                OT SURJEON AND STAFFS BILL AMOUNT DISTRIBUTION
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
    {!! Form::open(['url' =>'patientOTSuergeryStaffBillDistribution','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
<div class="row">
 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         PATIENT OT INFORMATION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                         
                                            <div class="form-body">
                                                   <div class="form-group">
                                                    <label class="col-md-2 control-label"></label>
                                                    <div class="col-md-10">
                                                      <strong>OT BOOK NO - OT TYPE - PATIENT NAME - PATIENT ID </strong>
                                                     </div>
                                                </div>
                                                <input type="hidden" name="booking_id" value="<?php echo $booking_id ; ?>" required="">
                                                <input type="hidden" name="patient_id" value="<?php echo $patient_id ; ?>" required="">
                                               
                                                            
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
               
                </div><!-- END PAGE CONTENT -->  
                <div class="row">
                 <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                         DOCTOR / SURGEON AMOUNT DISTRIBUTION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                                   <div class="form-group">
                                                   
                                                    <div class="col-md-4">
                                                      <strong>MAIN SURGEON </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong>PATIENT TOTAL GIVEN </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="main_surjon_fee" id="main_surjon_fee" required="" value="<?php echo $main_surjon_query->total_charge_amount ; ?>" readonly=""> </strong>
                                                     </div>
                                                </div>

                                                   <div class="form-group">
                                                   
                                                    <div class="col-md-12">
                      <div class="table-responsive">                     
                      <table class="table table-bordered">
                    <thead>   
                    <tr>       
                    <th>NAME</th>
                    <th>SPEIALIST</th>
                    <th>PAYABLE</th>
                    </tr>
                    </thead>
                     <tbody>
                      <?php foreach ($main_surjon as $main_surjon_value) { ?>
                     <tr>
                   <input class="form-control" type="hidden" name="main_surjon[]" id="main_sujon" value="<?php echo $main_surjon_value->id;?>" required="">
                    <td><?php echo $main_surjon_value->name ; ?></td>
                    <td><?php echo $main_surjon_value->speialist ; ?></td>
                    <td><input class="form-control" type="text" name="main_surjon_payable[]" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                </div>
                                                </div>

                                                  <div class="form-group">
                                                   
                                                    <div class="col-md-4">
                                                      <strong>ANESTHESIA </strong>
                                                     </div>
                                                      <div class="col-md-4">
                                                      <strong>PATIENT TOTAL GIVEN </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="anes_fee" id="anes_fee" required="" value="<?php echo $anes_surjon_query->total_charge_amount ; ?>" readonly=""> </strong>
                                                     </div>
                                                </div>
                                                   <div class="form-group">

                                                    <div class="col-md-12">

                                                         <div class="table-responsive">                     
                      <table class="table table-bordered">
                    <thead>   
                    <tr>  
                    <th>NAME </th>       
                    <th>SPEIALIST</th>
                    <th>PAYABLE</th>
                    </tr>
                    </thead>
                     <tbody>
                      <?php foreach ($anes_surjon as $anes_surjon_value) { ?>
                     
                     <tr>
                   <input class="form-control" type="hidden" name="anes_surjon[]" id="anes_surjon" value="<?php echo $anes_surjon_value->id;?>" required="">
                    <td><?php echo $anes_surjon_value->name ; ?></td>
                    <td><?php echo $anes_surjon_value->speialist ; ?></td>
                    <td><input class="form-control" type="text" name="anes_surjon_payable[]" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                </div>
                                                </div>
                                                   <div class="form-group">
                                                    
                                                    <div class="col-md-4">
                                                      <strong>Assistant Surgeon </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong>PATIENT TOTAL GIVEN </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="assistant_surjon_fee" id="assistant_surjon_fee" required="" value="<?php echo $assistant_surjon_query->total_charge_amount ; ?>" readonly=""> </strong>
                                                     </div>
                                                </div>
                                                   <div class="form-group">
                                                    <div class="col-md-12">
                                                         <div class="table-responsive">                     
                    <table class="table table-bordered">
                    <thead>   
                    <tr>         
                    <th>NAME</th>
                    <th>SPEIALIST</th>
                    <th>PAYABLE</th>
                    </tr>
                    </thead>
                     <tbody>
                      
                      <tr>
                      <?php foreach ($assistant_surjon as $assistant_surjon_value) { ?>
                    <tr>
                    <input class="form-control" type="hidden" name="assistant_surjon[]" id="assistant_surjon" value="<?php echo $assistant_surjon_value->id;?>" required="">
                    <td><?php echo $assistant_surjon_value->name ; ?></td>
                    <td><?php echo $assistant_surjon_value->speialist ; ?></td>
                    <td><input class="form-control" type="text" name="assistant_surjon_payable[]" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>
                                                      


                                                     </div>
                                                </div>                       
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                     <div class="col-md-6">
                                 <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                        OTHER STAFF AMOUNT DISTRIBUTION
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <div class="form-body">
                                                 <div class="form-group">
                                                    <div class="col-md-4">
                                                      <strong>OT ASSISTANT </strong>
                                                     </div>
                                                       <div class="col-md-4">
                                                      <strong>PATIENT TOTAL GIVEN </strong>
                                                     </div>
                                                     <div class="col-md-4">
                                                      <strong><input class="form-control" type="text" name="ot_assistant_fee" id="ot_assistant_fee" required="" value="<?php echo $ot_assistant_query->total_charge_amount ; ?>" readonly="" > </strong>
                                                     </div>
                                                </div>

                    <div class="table-responsive">                     
                    <table class="table table-bordered">
                    <thead>   
                    <tr>         
                    <th>NAME</th>
                    <th>MOBILE</th>
                    <th>PAYABLE</th>
                    </tr>
                    </thead>
                     <tbody>
                      <tr>
                      <?php foreach ($ot_assistant as $ot_assistant) { ?>
                    <tr>
                    <input class="form-control" type="hidden" name="ot_assistant[]" id="ot_assistant" value="<?php echo $ot_assistant->id;?>" required="">
                    <td><?php echo $ot_assistant->name ; ?></td>
                    <td><?php echo $ot_assistant->mobile ; ?></td>
                    <td><input class="form-control" type="text" name="ot_assistant_payable[]" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                    </tr>
                    <?php } ?>
                    </tbody>      
                    </table>
                  </div>


                                  <div class="form-group">
                                                    
                                                    <div class="col-md-4">
                                                      <strong>Remarks</strong>
                                                     </div>

                                                </div>
                                                   <div class="form-group">
                                                    <div class="col-md-12">
                                                      <textarea class="form-control" name="other_ot_info"></textarea>

                                                     </div>
                                                </div>
               
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END DASHBOARD STATS 1-->
                    </div><!-- END PAGE CONTENT BODY -->
                </div><!-- END PAGE CONTENT -->  

                <div class="row"> 
                      <div class="form-group">
                      <label class="col-md-4 control-label">Distribution Date<span style="color:red; font-weight: bold">*</span></label>
                      <div class="col-md-3">
                      <input class="form-control form-control-inline input-medium date-picker" data-date-format="dd-mm-yyyy"  type="text" id="tr_date" name="tr_date" required="">
                      </div>
                      </div>
                  <div class="form-group">
                    <div class="col-md-12">
                    <button type="submit" id="bill_create" class="form-control spinner btn btn-primary"> OT SURJEON  AND  STAFFS BILL DISTRIBUTION</button>
                    </div>
                   </div>
                {!! Form::close() !!}

            </div><!-- END CONTAINER -->
@endsection

