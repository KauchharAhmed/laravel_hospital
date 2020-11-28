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
            <td style="border: 0px;">Booking Date</td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php echo date('d M Y',strtotime($row->booking_date)) ; ?></span></td>
		</tr>
	  <tr style="border: 0px;">
			<td style="border: 0px;">Mobile No </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->patient_mobile ; ?></span></td>
            <td style="border: 0px;">Created Date </td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><?php echo date('d M Y',strtotime($row->created_at)) ; ?></span></td>
		</tr>
		<tr style="border: 0px;">
			<td style="border: 0px;">Age</td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->patient_age ; ?></span></td>
            <td style="border: 0px;">Bill Type </td>
            <td style="border: 0px;">: <span style="padding-left:50px;"> OT Booking</span> </td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;">Sex  </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php if($row->patient_sex == '1'){
			 	echo "Male";
			 }else{
			 	echo "Female";
			 } ; ?></span></td>
            <td style="border: 0px;"><strong>Bill No</strong></td>
            <td style="border: 0px;">: <span style="padding-left:50px;"><strong><?php echo " OTB- ". $row->invoice ; ?></strong></span></td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;">Address </td>
            <td style="border: 0px;">:<span style="padding-left:50px;"><?php echo $row->address;?></span></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"> <span style="padding-left:50px;"></span></td>
		</tr>

			<tr style="border: 0px;">
			<td style="border: 0px;"><strong>PATIENT ID </strong> </td>
            <td style="border: 0px;"><strong>:<span style="padding-left:50px;"><?php echo $row->patient_number;?></span> </strong></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"> <span style="padding-left:50px;"></span></td>
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
          $bill_barcode_info = "OTB-".$row->invoice;
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

		<table width="100%">
			<tr style="border: 0px;">
			<td style="border: 0px;"><strong>OT TYPE </strong>  </td>
            <td style="border: 0px;"> <strong>:<span style="padding-left:50px; "><?php echo $ot_type_query->ot_type ; ?></span></strong></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"> <span style="padding-left:200px;"></span></td>
		</tr>
			<tr style="border: 0px;">
			<td style="border: 0px;"><strong>ADVANCE PAYMENT</strong>  </td>
            <td style="border: 0px;"><strong> :<span style="padding-left:50px; "><?php echo $value->payment_amount ; ?></span></strong></td>
            <td style="border: 0px;"></td>
            <td style="border: 0px;"> <span style="padding-left:200px;"></span></td>
		</tr>
	</table>
	<div class="totalfixed">
	<br />
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
