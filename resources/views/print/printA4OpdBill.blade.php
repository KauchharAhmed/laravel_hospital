<?php
$admin_id       = Session::get('admin_id');
$type           = Session::get('type');
$branch_id      = Session::get('branch_id');
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
       if($branch_id == ''){
       return Redirect::to('/admin')->send();
       exit();
        }
        ?>
<html lang="en">
<head>
	<title>BILL</title>
	<meta charset="utf-8">
	<style type="text/css">
	@media print {
	body {-webkit-print-color-adjust: exact;}
	}	
	.wrapper{
		width:100%;
		height:auto;
		!background:pink !important;
		margin:0px auto;
	}
	.row1{
		width:60%;
		height:auto;
		!background:orange !important;
		float:left;
	}
	.row2{
		width:30%;
		height:auto;
		!background:green !important;
		float:right;
	}
	.row3{
		width:100%;
		height:auto;
		!background:green !important;
		float:right;
	}
	table.cut,tr.cut,td.cut{
		border-collapse: collapse;
		border:1px solid #000;
		padding:5px;
		font-family:tahoma;
		font-size:14px;
	}
	.first_row{
		width:100%;
		height:140px;
		margin-top:15px;
	}
	.second_row{
		width:100%;
		height:500px;
		margin-top:0px;
	}
	table.roni {
			border-collapse: none;
		}
	table.roni, td.roni, th.roni {
			border: none;
		}

	.text_main{
	text-align:center;
	margin:0;
	padding:0;
	line-height:5px;
	}
	.sompa ul li{
			float:left;
		}
	div.fixed {
			position: fixed;
			bottom: 0;
			right: 0;
			width:100%;
		}
		div.totalfixed {
			position: fixed;
			bottom: 0;
			right: 0;
			width:100%;
			padding-bottom: 100px;
		}
	</style>
</head>
<body> 

	<header>
		<div class="text_main"> 
			 <table width="100%" border="1px;">
       	<tr style="border: 0px;">
			<td style="border: 0px;width: 100px;"> <img width="200px;" style="padding-top:20px; height:100px" src="{{URL::to($row->logo)}}" alt="Logo"></td>
            <td style="border: 0px; margin-left:100px; " ><strong><?php echo $row->name ; ?></strong>
            	<br/><?php echo $row->address ; ?><br/>
            	Phone: <?php echo $row->mobile ; ?>
            </td>
            
		</tr>
	</table>
	<table width="100%" border="1px;">

       <tr style="border: 0px;">
			<td style="border: 0px;">Name  </td>
            <td style="border: 0px;"> :<span style="padding-left:50px; "><?php echo $row->patient_name ; ?></span></td>
            <td style="border: 0px;">Bill Date</td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php echo date('d  M Y',strtotime($row->bill_date)) ; ?></span></td>
		</tr>
	  <tr style="border: 0px;">
			<td style="border: 0px;">Mobile No </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->patient_mobile ; ?></span></td>
            <td style="border: 0px;">Doctor </td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php 
            // get doctor info
            $doctor_query = DB::table('admin')->where('id',$row->doctor_id)->first();
            echo $doctor_query->name ;
            ?></span></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Age</td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->patient_age ; ?></span></td>
            <td style="border: 0px;">Bill Type</td>
            <td style="border: 0px;">: <span style="padding-left:50px;">OPD</span> </td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;">Sex  </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php if($row->patient_sex == '1'){
			 	echo "Male";
			 }else{
			 	echo "Female";
			 } ; ?></span></td>
            <td style="border: 0px;"><strong>Bill No</strong></td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><strong><?php echo " OPD- ". $row->invoice ; ?></strong></span></td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;">Address </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->address;?></span></td>
            <td style="border: 0px;">Last Tr. Date</td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php echo date('d M Y',strtotime($bill_last_tr_no->tr_date)) ; ?></span></td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;"><strong>PATIENT ID </strong> </td>
            <td style="border: 0px;"><strong>:<span style="padding-left:50px;"><?php echo $row->patient_number;?></span> </strong></td>
            <td style="border: 0px;">Tr. No</td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php echo $bill_last_tr_no->invoice_tr_id ; ?></span></td>
		</tr>
	</table>

	<table width="100%" border="1px;">
       	<tr style="border: 0px;">
			<td style="border: 0px; padding-top: 10px">
			<?php 
			$bill_patient_info = "P-".$row->patient_number ;
			 echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($bill_patient_info, "C39",1,33) . '" alt="barcode"   />';
			?>
        </td>
            <td style="border: 0px; padding-top: 10px;float: right;">

          <?php
          $bill_barcode_info = "OPD-".$row->invoice;
           echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($bill_barcode_info, "C39",1,33) . '" alt="barcode"   />';
        ?>

          	</td>
		</tr>

		  	<tr style="border: 0px;">
			<td style="border: 0px; padding-top: 10px">
			<?php 
			echo $bill_patient_info ;
			?>
  
            <td style="border: 0px; padding-top: 10px;float: right;">

           <?php
          echo  $bill_barcode_info ;
        ?>
          	</td>
		</tr>


	</table>

		</div>
	</header>	
	<br/>


<table width="100%" class="roni">
		<tr class="cut" style="background-color: #F6F1F0 ;">
			<td>No</td>
            <td>Test Name</td>
            <td>Amount</td>
            <td>Subtotal</td>
		</tr>
		<?php $j = 1 ; foreach ($result as $value) { ?>
			<tr class="cut">
			    <td><?php echo $j++; ?></td>
                <td><?php echo $value->opd_name; ?></td>
                <td><?php echo $value->opd_fee; ?></td>
                <td><?php echo number_format($value->total_price,2) ?></td>
			</tr>
			<?php } ?>
			</table>
			<div class="totalfixed">
			<table width="100%" style="padding-right:30px;">
			<tr>
				<td colspan="8"><hr align="right" width="68%"></td>
			</tr>
		    <?php // bill tr info
           $total_payable  = 0 ;
           $total_payment  = 0 ;
           $total_discount = 0 ;
           $total_rebate   = 0 ;
            foreach ($bill_tr_info as $bill_tr_info_value) {
            	$total_payable  = $total_payable + $bill_tr_info_value->total_payable ;
            	$total_payment  = $total_payment + $bill_tr_info_value->total_payment ;
            	$total_discount = $total_discount + $bill_tr_info_value->total_discount ;
            	$total_rebate   = $total_rebate + $bill_tr_info_value->total_rebate ;	
            }
                 $total_return_amt = 0 ;
            foreach ($bill_return_amount as $return_amt_value) {
            	$total_return_amt = $total_return_amt + $return_amt_value->total_discount ;
            }
            ?>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total</strong></td>
                <td><strong style="padding-left: 0px;"><?php  echo number_format($total_payable,2); ?></strong></td>
			</tr>
			<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>(-) Discount</strong></td>
                <td><strong><?php echo number_format($total_discount,2); ?></strong></td>
			</tr>
			<?php if($total_rebate > 0) { ?>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>(-) Rebate</strong></td>
                <td><strong>><?php echo number_format($total_rebate,2); ?></strong></td>
			</tr>
			<?php } ?>
			<tr>
				
               <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Net Payable</strong></td>
                <td><strong><?php 
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is = $total_payable - $all_discount_rebate ;
                echo number_format ($net_payable_is ,2) ; ?></strong></td>
			</tr>
			<tr>
				
                <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Amount Received</strong></td>
                <td><strong><?php 
                $amt_received_is = $total_payment - $total_return_amt ;
                echo number_format($amt_received_is,2); ?></strong></td>
			</tr>
			
			<tr>
			
                <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Balance Due</strong></td>
                <td><strong><?php 
                $due_amount = $net_payable_is - $amt_received_is ;
                echo number_format($due_amount,2); ?></strong></td>
			</tr>
		
			<tr>
				
               
                <td></td>
                <td></td>
                <td><strong></strong></td>
                <td><strong></strong></td>
			</tr>
			<tr>
				
           
                <td></td>
                <td></td>
                <td><strong></strong></td>
                <td><strong></strong></td>
			</tr>

		<tr>				
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td style="!border:1px solid black;padding-left:100px;"><strong>
			<span style="color:red ; border: 1px;"></span>
			<span style="color:green ; border: 1px;"></span>
			</strong></td>
			<td><strong></strong></td>
		</tr>
	</span>

       </table>


	<br />
	<center><strong><?php if($due_amount> 0): ?></strong><span style="color:red; border: 1px solid #000;padding:9px;border-radius:4px;font-size:21px;font-family:arial;">DUE</span><strong><?php else:?><span style="color:green; border: 1px solid #000;padding:9px;border-radius:4px;font-size:21px;font-family:arial;">PAID</span><?php endif; ?></strong></td>
			<td><strong></strong></center>	
				<div>

	   
	<div class="fixed">
			<div>
				<span style="float:left !important;font-family:arial;">Prepared By : <?php echo $row->admin_name ; ?></span>
				<span style="float:right !important;font-family:arial;border-bottom:1px solid #000; margin-top: 70px;">Authorized By</span>
			</div>
			<br/><br/><br/><br/><br/>
			<div style="border-bottom:1px solid #000;border-top:1px solid #000;width:100%;margin-top:4px;!border:1px solid #333;font-size:14px;">
			   <center><span style="text-align:center;font-family:tahoma;">Developed By: ASIAN IT INC(www.asianitinc.com)</span></center>
			</div>
		</div>

	<script type="text/javascript">
	window.print();
	</script>
</body>
</html>			
