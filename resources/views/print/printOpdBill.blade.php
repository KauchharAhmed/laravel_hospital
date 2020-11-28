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

		@page { size: landscape }
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
		font-size:11px;
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

	table.roni, td.roni, th.roni {
		border-collapse: none;
		border: none;
		font-size:10px !important;
	}

	table.rayhan, td.rayhan, th.rayhan {
		font-size:12px;
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

	div.fixedOne {
		position: fixed;
		bottom: 0;
		right: 0;
		width:50%;
	}

	div.totalfixed {
		position: fixed;
		bottom: 0;
		right: 0;
		font-size:11px;
		font-weight: normal !important;
		font-family: tahoma;
		width:100%;
		padding-bottom: 100px;
	}

.ad-left {
  float: left;
}

.ad-right {
  float: right;
  margin-left: 10px;
}

.entire-thing {
  width: 100%;
}
</style>
</head>
<body> 
<table width="100%">
	<tr>
	<td width="47%" style="float: left;">
	<header>
	<div class="text_main">
	<table width="95%" border="1px;" style="font-size: 11px;font-family:tahoma;">
		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"><img width="65px;" style="height:35px" src="{{URL::to($row->logo)}}" alt="Logo"></td>
            <td style="border: 0px;font-size: 9px;font-weight: bold;"><?php echo $row->name ; ?></td>
		</tr>
		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;font-weight: bold; font-size: 8px"><?php echo $row->address ; ?></td>
		</tr>
		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;font-weight: bold; font-size: 8px"><?php echo $row->mobile ; ?></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Name <span style="padding-left: 3px;">:</span> <?php echo $row->patient_name ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Date <span style="padding-left: 37px;">:</span> <?php echo $row->bill_date ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Mobile : <?php echo $row->patient_mobile ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Doctor <span style="padding-left: 27px;">:</span> <?php 
            // get doctor info
            $doctor_query = DB::table('admin')->where('id',$row->doctor_id)->first();
            echo $doctor_query->name ;
            ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Age <span style="padding-left: 13px;">:</span> <?php echo $row->patient_age ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Bill Type <span style="padding-left: 17px;">:</span> OPD</td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Sex <span style="padding-left: 14px;">:</span> <?php
			 if($row->patient_sex == '1'){
			 	echo "Male";
			 }else{
			 	echo "Female";
			 } ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;font-weight: bold;">Bill No <span style="font-weight: bold;padding-left: 22px;">:</span> <?php echo "#". $row->invoice ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Patient Id : <?php echo $row->patient_number;?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;font-weight: bold;">Tr. No <span style="padding-left: 24px;">:</span> <?php echo $bill_last_tr_no->invoice_tr_id ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		
	</table>
	<table width="95%" border="1px;" style="font-size: 11px;">
       	<tr style="border: 0px;">
			<td style="border: 0px; padding-top: 10px">
			<?php 
			$bill_patient_info = "PATIENT ID-".$row->patient_number ;
			?>
			 <img style="width:107px" src="data:image/png;base64,{{DNS1D::getBarcodePNG($bill_patient_info, 'C39')}}" alt="barcode"/>
        <?php
          $bill_barcode_info = "OPD-".$row->invoice;
        ?>
          <img style="width:107px;float: right;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($bill_barcode_info, 'C39')}}" alt="barcode"/>
   </td>   
	</tr>
	   	<tr style="border: 0px;">
			<td style="border: 0px;">
			<span><?php echo $bill_patient_info;?></span>
            <span style="float: right;"><?php echo $bill_barcode_info ; ?></span>
   </td>   
	</tr>

	</table>
		</div>
	</header>	
	<br/>
<table width="95%" class="roni" style="font-size: 11px;font-family:tahoma;">
		<tr class="cut" style="background-color: #F6F1F0 ;">
			<td>No</td>
            <td>OPD Category</td>
            <td>Amount</td>
            <td>Qty</td>
            <td>Subtotal</td>
		</tr>
		<?php $j = 1 ; foreach ($result as $value) { ?>
			<tr class="cut">
				<td><?php echo $j++; ?></td>
                <td><?php echo $value->opd_name; ?></td>
                <td><?php echo $value->opd_fee; ?></td>
                <td><?php echo $value->total_quantity; ?></td>
                <td><?php echo number_format($value->total_price,2) ?></td>
			</tr>
			<?php } ?>
			</table>
			<div class="totalfixed">
			<table width="45%" style="margin-left: 48px;" class="rayhan">
			<tr>
				<td colspan="8"><hr align="right" width="95%"></td>
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
           ?>
			<span>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td><?php  echo number_format($total_payable,2); ?></td>
			</tr>
			<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>(-) Discount</td>
                <td><?php echo number_format($total_discount,2); ?></td>
			</tr>
			<?php if($total_rebate > 0) { ?>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>(-) Rebate</td>
                <td><?php echo number_format($total_rebate,2); ?></td>
			</tr>
			<?php } ?>
			<tr>
               <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Net Payable</td>
                <td><?php 
                $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is = $total_payable - $all_discount_rebate ;
                echo number_format ($net_payable_is ,2) ; ?></td>
			</tr>
			<tr>
                <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Amount Received</td>
                <td><?php echo number_format($total_payment,2); ?></td>
			</tr>
			<tr>
                <td></td>
		        <td></td>
		        <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Balance Due</td>
                <td><?php 
                $due_amount = $net_payable_is - $total_payment ;
                echo number_format($due_amount,2); ?></td>
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
<table width="47%" style="position: fixed;bottom: 30px;" class="rayhan">
		<tr>
			<td><span style="font-family:arial;">Prepared By : <?php echo $row->admin_name ; ?></span></td>
			<td><span style="font-family:arial;float: right;">Authorized By</span></td>
		</tr>
	</table>
	<table width="47%" style="position: fixed;bottom: 0px;" class="rayhan">
		<tr>
			<td><span style="font-family:arial;padding-left: 25%">Developed By: ASIAN IT INC. (www.asianitinc.com)</span></td>
		</tr>
	</table>			
		</td>
		<td width="47%" style="float: right;">
	<header>
    <div class="text_main">
	<table width="95%" border="1px;" style="font-size: 11px;font-family:tahoma;">

		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"><img width="65px;" style="height:35px" src="{{URL::to($row->logo)}}" alt="Logo"></td>
            <td style="border: 0px; font-size: 9px;font-weight: bold;"><?php echo $row->name ; ?></td>
		</tr>
		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;font-weight: bold;font-size: 8px;"><?php echo $row->address ; ?></td>
           
		</tr>
		<tr colspan="2" style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;font-weight: bold;font-size: 8px;"><?php echo $row->mobile ; ?></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;"></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Name <span style="padding-left: 3px;">:</span> <?php echo $row->patient_name ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Date <span style="padding-left: 37px;">:</span> <?php echo $row->bill_date ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Mobile : <?php echo $row->patient_mobile ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Doctor <span style="padding-left: 27px;">:</span> <?php $doctor_query = DB::table('admin')->where('id',$row->doctor_id)->first();
            echo $doctor_query->name ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Age <span style="padding-left: 13px;">:</span> <?php echo $row->patient_age ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;">Bill Type <span style="padding-left: 17px;">:</span> OPD</td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Sex <span style="padding-left: 14px;">:</span> <?php echo $row->patient_sex ; ?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;font-weight: bold;">Bill No <span style="padding-left: 22px;font-weight: bold;">: </span><?php echo "#". $row->invoice ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Patient Id : <?php echo $row->patient_number;?></td>
            <td style="border: 0px;"><strong></strong></td>
            <td style="border: 0px;font-weight: bold;">Tr. No <span style="padding-left: 24px;">:</span> <?php echo $bill_last_tr_no->invoice_tr_id ; ?></td>
            <td style="border: 0px;"></td>
		</tr>
	</table>
	<table width="95%" border="1px;" style="font-size: 11px;">
      	<tr style="border: 0px;">
			<td style="border: 0px; padding-top: 10px">
			<?php 
			$bill_patient_info = "PATIENT ID-".$row->patient_number ;
			?>
			 <img style="width:107px" src="data:image/png;base64,{{DNS1D::getBarcodePNG($bill_patient_info, 'C39')}}" alt="barcode"/>
        <?php
          $bill_barcode_info = "OPD-".$row->invoice;
        ?>
          <img style="width:107px;float: right;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($bill_barcode_info, 'C39')}}" alt="barcode"/>
   </td>   
	</tr>
	   	<tr style="border: 0px;">
			<td style="border: 0px;">
			<span><?php echo $bill_patient_info;?></span>
            <span style="float: right;"><?php echo $bill_barcode_info ; ?></span>
   </td>   
	</tr>
	</table>
		</div>
	</header>	
	<br/>
<table width="95%" class="roni" style="font-size: 11px;font-family:tahoma;">
		<tr class="cut" style="background-color: #F6F1F0 ;">
			<td>No</td>
            <td>OPD Category</td>
            <td>Amount</td>
            <td>Qty</td>
            <td>Subtotal</td>
		</tr>
		<?php $j = 1 ; foreach ($result as $value) { ?>
			<tr class="cut">
				<td><?php echo $j++; ?></td>
                <td><?php echo $value->opd_name; ?></td>
                <td><?php echo $value->opd_fee; ?></td>
                <td><?php echo $value->total_quantity; ?></td>
                <td><?php echo number_format($value->total_price,2) ?></td>
			</tr>
			<?php } ?>
			</table>


			<div class="totalfixed" style="float: right;">
			<table width="110%" style="margin-left: 48px;" class="rayhan">
			<tr>
				<td colspan="5"><hr align="right" width="50%"></td>
			</tr>
			<span>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">Total</td>
                <td style="padding-right: 150px;"><?php  echo number_format($total_payable,2); ?></td>
			</tr>
			<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">(-) Discount</td>
                <td style="padding-right: 150px;"><?php echo number_format($total_discount,2); ?></td>
			</tr>
			<?php if($total_rebate > 0) { ?>
				<tr>
		        <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">(-) Rebate</td>
                <td style="padding-right: 150px;"><?php echo number_format($total_rebate,2); ?></td>
			</tr>
			<?php } ?>
			<tr>
				
               <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">Net Payable</td>
                <td style="padding-right: 150px;"><?php $all_discount_rebate = $total_discount + $total_rebate ;
                $net_payable_is = $total_payable - $all_discount_rebate ;
                echo number_format ($net_payable_is ,2) ;  ?></td>
			</tr>
			<tr>
				
                <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">Amount Received</td>
                <td style="padding-right: 150px;"><?php echo number_format($total_payment,2); ?></td>
			</tr>
			
			<tr>
			
                <td></td>
		        <td></td>
		        <td></td>
                <td style="padding-left: 550px;">Balance Due</td>
                <td style="padding-right: 150px;"><?php $due_amount = $net_payable_is - $total_payment ;
                echo number_format($due_amount,2);  ?></td>
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
	<table width="100%" style="position: fixed;bottom: 30px;" class="rayhan">
		<tr>
			<td><span style="font-family:arial;padding-left: 540px;">Prepared By : <?php echo $row->admin_name ; ?></span></td>
			<td><span style="font-family:arial;float: right;padding-left: 200px;">Authorized By</span></td>
		</tr>
	</table>

	<table width="100%" style="position: fixed;bottom: 0px;" class="rayhan">
		<tr>
			<td><span style="font-family:arial;padding-left: 67%">Developed By: ASIAN IT INC. (www.asianitinc.com)</span></td>
		</tr>
	</table>			
		</td>
	</tr>
</table>
<script type="text/javascript">
window.print();
</script>
</body>
</html>