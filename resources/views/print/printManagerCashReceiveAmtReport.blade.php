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
        <tr>
          <td><center><img style="padding-top:0px !important;" width="200" height="100" src="{{URL::to($value1->image)}}"  alt="" /></center></td>
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
      <center><h3><span style="font-family:tahoma;border:1px solid #000;padding-top:4px;padding-bottom:4px;padding-left:27px;padding-right:27px;">CASH RECEIVE REPORT</span></h3></center>      
      <div class="row">
          <table style="font-size:12px;">
                                    <tr>
                                        <td><strong> CASH RECEIVE REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
                                          <?php 
                                          echo "FOR ";
                                          if($transfer_type == '0'){
                                            echo "PENDING";
                                          }elseif($transfer_type == '1'){
                                            echo "APPROVED";
                                          }elseif($transfer_type == '2'){
                                            echo "REJECTED";
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
                                                    <th class="nila">Transfer Date</th>
                                                    <th class="nila">Transfer Type</th>
                                                    <th class="nila">Remarks</th>
                                                    <th class="nila">Receive Amt</th>
  </tr>
  </thead>
  <tbody>
                                                <?php $i = 1 ;
                                                $total_transfer_amt = 0 ;
                                                foreach ($result as $value) { $total_transfer_amt = $total_transfer_amt + $value->transfer_amount ;
                                                 ?>
                                                <tr>
                                                    <td class="nila"><?php echo $i++ ;?></td>
                                                    <td class="nila"><?php echo date('d-M-Y',strtotime($value->transfer_date)) ; ?></td>
                                                    <td class="nila"><?php
                                                    if($value->status == '0'):?>
                                                    <span style="color:green">PENDING</span>
                                                  <?php elseif($value->status == '1'):?>
                                                    <span style="color:blue">APPROVED</span>
                                                    <?php elseif($value->status == '2'):?>
                                                    <span style="color:red">REJECTED</span>
                                                     <?php endif;?></td>
                                                    <td class="nila"><?php echo $value->remarks ; ?></td>
                                                    <td class="nila"><?php echo $value->transfer_amount ; ?></td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="4" class="nila"><strong>TOTAL</strong></td>
                                                    <td class="nila"><strong><?php echo number_format($total_transfer_amt,2);?></strong></td>
                                                </tr>
</tbody>
</table>
	<script type="text/javascript">
	window.print();
	</script>
    </body>
</html>

   