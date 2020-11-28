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
                                 MEDICAL PRODUCT
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
                                          Add Medical Product
                                      </div>
                                    </div>
                                    <div class="portlet-body form">
                                             {!! Form::open(['url' =>'addProductInfo','method' => 'post','role' => 'form','class'=>'form-horizontal','files' => true]) !!}
                                            <div class="form-body">
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Product Name <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="product" id="product" required="">
                                             
                                                </div>
                                              </div>
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Product Code <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control spinner" name="product_code" id="product_code" value="<?php echo $product_code ;?>" required="">
                                             
                                                </div>
                                              </div>
                                                  <div class="form-group">
                                                    <label class="col-md-4 control-label">Purchase Price <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="purchase_price" id="purchase_price" required="" step="any">
                                             
                                                </div>
                                              </div>
                                               <div class="form-group">
                                                    <label class="col-md-4 control-label">Confirm Purchase Price <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control spinner" name="confirm_purchase_price" id="purchase_price" required="" step="any">
                                             
                                                </div>
                                              </div>
  
                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Unit <span style="color:red; font-weight: bold">*</span></label>
                                                    <div class="col-md-8">
                                                      <select class="form-control spinner" name="unit" id="unit" required="">
                                                       <option value="">Select Unit</option>
                                                       <?php foreach ($unit as $units) { ?>  
                                                       <option value="<?php echo $units->id ;?>"><?php echo $units->unit_name ;?></option> 
                                                       <?php } ?>
                                                      </select>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Description</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="description" placeholder="Description"></textarea>
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Remarks</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control spinner" name="remarks" placeholder="Remarks"></textarea>
                                                        </div>
                                                </div>

                                                    <div class="form-group">
                                                    <label class="col-md-4 control-label">Product Image</label>
                                                    <div class="col-md-8">
                                                      <input type="file" class="form-control spinner" name="image">
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
    $('#category').change(function(e){
    e.preventDefault();
    var category    = $(this).val();
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
       $.ajax({
        'url':"{{ url('/getBrandByCategory') }}",
        'type':'post',
        'dataType':'text',
        data:{  
        category:category
        },
         success:function(data)
         {
            $('#brand').html(data);
        }
        });
       });
    // grand price total  by damage key up
    $('#damage_price').keyup(function(e){
    e.preventDefault();
    var damage_price      = parseInt($(this).val());
    var purchase_price    = $('#purchase_price').val();
    var utility_price     = $('#utility_price').val();
    var purchase_pricee   = parseInt($('#purchase_price').val());
    var utility_pricee    = parseInt($('#utility_price').val());
    if(purchase_price == ''){
      alert('Select Purchase Price');
      return false;
    }
    if(purchase_price == ''){
      alert('Select Utility Price');
      return false;
   }
   var grand_price = purchase_pricee + utility_pricee + damage_price ;
   $('#grand_price').val(grand_price);
    })
    // grand price total by purchase key up
    $('#purchase_price').keyup(function(e){
    e.preventDefault();
    var purchase_pricee     = parseInt($(this).val());
    var damage_price        = $('#damage_price').val();
    var utility_price       = $('#utility_price').val();

    var damage_pricee     = parseInt($('#damage_price').val());
    var utility_pricee    = parseInt($('#utility_price').val());
    if(damage_pricee == ''){
      alert('Select Damage Price');
      return false;
    }
    if(utility_pricee == ''){
      alert('Select Utility Price');
      return false;
   }
   var grand_price = purchase_pricee + utility_pricee + damage_pricee ;
   $('#grand_price').val(grand_price);
    })
    // grand price total by utililty key up
    $('#utility_price').keyup(function(e){
    e.preventDefault();
    var utility_pricee      = parseInt($(this).val());
    var damage_price        = $('#damage_price').val();
    var purchase_price      = $('#purchase_price').val();

    var damage_pricee     = parseInt($('#damage_price').val());
    var purchase_pricee   = parseInt($('#purchase_price').val());
    if(damage_pricee == ''){
      alert('Select Damage Price');
      return false;
    }
    if(purchase_price == ''){
      alert('Select Purchase Price');
      return false;
   }
   var grand_price = purchase_pricee + utility_pricee + damage_pricee ;
   $('#grand_price').val(grand_price);
    })



  



</script>
@endsection