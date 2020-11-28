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
      <center><h3><span style="font-family:tahoma;border:1px solid #000;padding-top:4px;padding-bottom:4px;padding-left:27px;padding-right:27px;">IPD SERVICE BILL REPORT</span></h3></center>      
      <div class="row">
          <table style="font-size:12px;">
                                    <tr>
                                        <td><strong> IPD SERVICE BILL REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?> 
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
                                                    <th class="nila">Bill Date</th>
                                                    <th class="nila">Admission No</th>
                                                    <th class="nila">Bill No</th>
                                                    <th class="nila">Patient Id</th>
                                                    <th class="nila">Patient Name</th>
                                                    <th class="nila">Bill Amt</th>
                                                    <th class="nila">Discount Amt</th>
                                                    <th class="nila">Rebate Amt</th>
                                                    <th class="nila">Payment Amt</th>
                                                </tr>
  </tr>
  </thead>
  <tbody>
                                                <?php $i = 1 ;
                                                foreach ($result as $value) { 
                                                 ?>
                                                <tr>
                                                    <td class="nila"><?php echo $i++ ;?></td>
                                                    <td class="nila"><?php echo date('d-M-Y',strtotime($value->bill_date)) ; ?></td>
                                                    <td class="nila"><?php 
                                                    $ipd_admit_query = DB::table('tbl_ipd_admission')->where('id',$value->ipd_admission_id)->first();
                                                    echo "IPDA- ".$ipd_admit_query->invoice ;
                                                     ?></td>
                                                    <td class="nila"><?php echo "IPDS- ".$value->invoice ; ?></td>
                                                    <td class="nila"><?php echo $value->patient_number ; ?></td>
                                                    <td class="nila"><?php echo $value->patient_name ; ?></td>
                                                    <?php
                                                    // get tr info
                                                     $ipd_admission_tr_transaction = DB::table('tbl_ipd_ledger')->where('branch_id',$branch_id)->where('ipd_admission_id',$value->ipd_admission_id)->where('service_invoice',$value->invoice)->where('service_id',$value->id)->where('service_type',3)->first(); 
                                                    ?>
                                                    <td class="nila"><?php echo $ipd_admission_tr_transaction->payable_amount ;  ?></td>
                                                    <td class="nila"><?php echo $ipd_admission_tr_transaction->discount ;  ?></td>
                                                    <td class="nila"><?php echo $ipd_admission_tr_transaction->rebate ;  ?></td>
                                                    <td class="nila"><?php echo $ipd_admission_tr_transaction->payment_amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>

                                                <tr>
                                                    <td colspan="6" class="nila"><strong>TOTAL</strong></td>
                                                     <td class="nila"><strong><?php echo number_format($total_payable_amt,2);?></strong></td>
                                                      <td class="nila"><strong><?php echo number_format($total_discount_amt,2);?></strong></td>
                                                       <td class="nila"><strong><?php echo number_format($total_rebate_amt,2);?></strong></td>
                                                       <td class="nila"><strong><?php echo number_format($total_payment_amt,2);?></strong></td>      
                                                </tr>
</tbody>
</table>
	<script type="text/javascript">
	window.print();
	</script>
    </body>
</html>

   