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
                                     WARD
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
                                          Add Ward
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                        	 {!! Form::open(['url' =>'addWardInfo','method' => 'post','role' => 'form','class'=>'form-horizontal']) !!}
                                            <div class="form-body">
                                                 <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Building<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                             <select class="form-control spinner selectpicker" data-live-search="true" name="building_id" id="building_id" required="">
                                                             <option value="">Select Building</option>
                                                             <?php
                                                             foreach ($building as $building_value) { ?>
                                                             <option value="<?php echo $building_value->id;?>"><?php echo $building_value->building_name ;?></option>
                                                             <?php } ?> 
                                                      </select>
                                                    </div>
                                                </div>

                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Select Floor<span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <select class="form-control" name="floor_id" id="floor_id" required=""> 
                                                      </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Ward No <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="name" placeholder="Ward No" required=""></div>
                                                </div>
                                                   <div class="form-group">
                                                    <label class="col-md-4 control-label">Bed Nic Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="nic_name" placeholder="Bed Nic Name " required=""></div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Bed Numbers <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="bed_number" placeholder="Bed Numbers" required=""></div>
                                                </div>
                                                       <div class="form-group">
                                                    <label class="col-md-4 control-label">Charge Amt (Per Bed) <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="amount" placeholder="Charge Amt (Per Bed)" required=""></div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Charge Amt (Per Bed) <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_amount" placeholder="Confirm Charge Amt (Per Bed)" required=""></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
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
@section('js')
<script>
      $("#building_id").change(function(e){
       e.preventDefault();
       var building_id         = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getFloorByBuildingId') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        building_id:building_id
        },
         success:function(data)
         {
          $("#floor_id").html(data);
        }
        });
       });   
</script>
@endsection