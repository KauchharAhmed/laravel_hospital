<?php
$admin_id       = Session::get('admin_id');
$type           = Session::get('type');
       
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
    <?php
     foreach ($result as $value1) {
     }
    ?>
        <tr>
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
      <center><h3><span style="font-family:tahoma;border:1px solid #000;padding-top:4px;padding-bottom:4px;padding-left:27px;padding-right:27px;">PC PAYMENT REPORT</span></h3></center>      
      <div class="row">
          <table style="font-size:12px;">
                                    <tr>
                                        <td><strong>PC PAYMENT REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php if($pc !=''){
                                            $pc_query = DB::table('tbl_pc')->where('id',$pc)->first();
                                            echo "FOR ".$pc_query->name ;
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
                                                    <th class="nila">Sl No</th>
                                                    <th class="nila">Payment Date</th>
                                                    <th class="nila">Memo No</th>
                                                    <th class="nila">PC Name</th>
                                                    <th class="nila">PC Mobile</th>
                                                    <th class="nila">Payment Method</th>
                                                    <th class="nila">Payment Amt</th>
  </tr>
  </thead>
  <tbody>
                                                <?php $i = 1 ;
                                                $grand_total_payment = 0 ;
                                                foreach ($result as $value) {
                                                 $grand_total_payment = $grand_total_payment + $value->payment_amount ;
                                                  ?>
                                                <tr>
                                                    <td class="nila"><?php echo $i++ ;?></td>
                                                    <td class="nila"><?php echo date('d-M-Y',strtotime($value->created_at)) ; ?></td>
                                                    <td class="nila"><?php echo $value->memo_no ; ?></td>
                                                    <td class="nila"><?php echo $value->pc_name ; ?></td>
                                                    <td class="nila"><?php echo $value->pc_mobile ; ?></td>
                                                    <td class="nila"><?php if($value->payment_method == '1'){
                                                      echo "CASH";
                                                    }elseif($value->payment_method == '3'){
                                                     echo "BANK";
                                                    }  ?></td>
                                                    <td><?php echo $value->payment_amount ;  ?></td>   
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                  <td colspan="6" class="nila"><strong>TOTAL</strong></td>
                                                  <td class="nila"><strong><?php echo number_format($grand_total_payment,2);?></strong></td>          
                                                </tr>
</tbody>
</table>
	<script type="text/javascript">
	window.print();
	</script>
    </body>
</html>

   