<?php
$admin_id       = Session::get('admin_id');
$type           = Session::get('type');
       
       if($admin_id == null && $type == null){
       return Redirect::to('/admin')->send();
       exit();
        }

       if($admin_id == null && $type != '3'){
       return Redirect::to('/admin')->send();
       exit();
        }
        
        if($type != '3'){
       return Redirect::to('/admin')->send();
       exit();
        }

        ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Print</title>
	<style type="text/css">
		table.nila {
			border-collapse: collapse;
		}

		table.nila, td.nila, th.nila {
			border: 1px solid black;
			padding:7px;
		}
	</style>
</head>
<body>
   <strong><table>
        <tr>
            <?php foreach ($result as $value1) {
                
              } ?>
          <td><center><img style="padding-top:0px !important;" width="200" height="100" src="{{URL::to($value1->logo)}}"  alt="" /></center></td>
          <td style="padding-left:315px !important;">
            <div>
            
            <span>
              <?php echo $value1->name ; ?>
            </span>
            <br>
            <span style="font-size:13px;">
              Address : <?php echo $value1->address ; ?>
            </span>
            <br/>
            <span style="font-size:13px;">
              Cell <span style="padding-left: 21px;">:</span> <?php echo $value1->mobile ; ?>
            </span>
            </div>          
          </td>
        </tr>
      </table>
    </strong>
      <center><h3><span style="font-family:tahoma;border:1px solid #000;padding-top:4px;padding-bottom:4px;padding-left:27px;padding-right:27px;">OT BOOKING REPORT</span></h3></center>      
      <div class="row">
          <table style="font-size:12px;">
                                    <tr>
                                        <td><strong> OT BOOKING REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($ot_type != ''){
                                           $ot_type_indi_query = DB::table('tbl_ot_type')->where('branch_id',$branch_id)->where('id',$ot_type)->first();
                                                    echo "FOR ".$ot_type_indi_query->ot_type ;
                                              }
                                                     ?>
                                        </strong></td>
                                     
                                    </tr>
                                    <tr>
                                        <td><?php echo "Print : ".date('d-m-Y , h:i:s a') ; ?></td>
                                    </tr>
                                </table>

<table width="100%" class="nila">
  <thead>
	<tr>
 <tr>
                                                    <th class="nila">Sl No</th>
                                                    <th class="nila">Booking Date</th>
                                                    <th class="nila">Booking No</th>
                                                    <th class="nila">OT Type</th>
                                                    <th class="nila">Patient Id</th>
                                                    <th class="nila">Patient Name</th>
                                                    <th class="nila">Advance Payment Amt</th>
                                                </tr>
  </tr>
  </thead>
  <tbody>
                                               <?php $i = 1 ;
                                                foreach ($result as $value) { 
                                                 ?>
                                                <tr>
                                                    <td class="nila"><?php echo $i++ ;?></td>
                                                    <td class="nila"><?php echo date('d-M-Y',strtotime($value->booking_date)) ; ?></td>
                                                    <td class="nila"><?php echo "OTB- ".$value->invoice ; ?></td>
                                                    <td class="nila"><?php $ot_type_query = DB::table('tbl_ot_type')->where('branch_id',$branch_id)->where('id',$value->ot_type)->first();
                                                    echo $ot_type_query->ot_type ;
                                                     ?></td>
                                                    <td class="nila"><?php echo $value->patient_number ; ?></td>
                                                    <td class="nila"><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                     $ot_booking_tr_transaction = DB::table('tbl_ot_ledger')->where('branch_id',$branch_id)->where('ot_booking_id',$value->id)->where('service_invoice',$value->invoice)->where('service_id',$value->id)->where('service_type',1)->first(); 
                                                    ?>
                                                    <td class="nila"> <?php echo $ot_booking_tr_transaction->payment_amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="6" class="nila"><strong>TOTAL</strong></td>
                                                       <td class="nila"><strong><?php echo number_format($total_payment_amt,2);?></strong></td>      
                                                </tr>
</tbody>
</table>
	<script type="text/javascript">
	window.print();
	</script>
    </body>
</html>

   