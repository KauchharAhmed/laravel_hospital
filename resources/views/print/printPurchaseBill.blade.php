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
	<title>Print Invoice</title>
		<style>
        .font_sml{ font-size:11px;}
        .table_tr_td {
            border-top: 1px solid #dddddd;
            line-height: 1.42857;
            padding: 7px;
            vertical-align: top;
        }
		table.nila {
			border-collapse: collapse;
		}

		table.nila, td.nila, th.nila {
			border: 1px solid black;
		}
		
		table.roni {
			border-collapse: none;
		}

		table.roni, td.roni, th.roni {
			border: none;
		}	
		.siam ul li{
			float:left;
		}
		div.fixed {
			position: fixed;
			bottom: 0;
			right: 0;
			width:100%;
		}	

		.table > thead > tr > th,
		.table > tbody > tr > th,
		.table > tfoot > tr > th,
		.table > thead > tr > td,
		.table > tbody > tr > td,
		.table > tfoot > tr > td {
		  padding: 8px;
		  line-height: 1.42857143;
		  vertical-align: top;
		  border-top: 1px solid #000;
		}
		.table > thead > tr > th {
		  vertical-align: bottom;
		  border-bottom: 2px solid #000;
		}	
		</style>	
</head>
<body>
	<div class="container-fluid">
      <div class="row">
        <div class="col-md-12" style="!border:1px dashed #333;">
          <div class="block-web">
           <div class="header">
			<table>
				<tr>
					<td><center><!--<img style="padding-top:0px !important;" width="100" height="100" src=""  alt="" />--></center></td>
					<td style="padding-left:370px !important;">
						<div>
						<span><?php  echo $row->name ; ?> </span><br>
						<span style="font-size:14px;">
							<?php echo $row->address ;?>
						</span>
						<br/>
						<span style="font-size:14px;">
							Mobile : <?php echo $row->mobile ;?>
						</span>
						</div>					
					</td>
				</tr>
			</table>
            </div>
			<center><h3>PURCHASE INVOICE</h3></center>			
			<div class="row">
				<div class="col-xs-12">
			<div class="col-md-6">
            <b>INVOICE NO : # <?php echo $row->invoice ; ?></b>
          </div>
          <div class="col-md-6">
            <b>DATE : <?php echo date("d-m-Y",strtotime($row->purchase_date )) ; ?></b>
          </div>
          <br/>
					<table style="font-size:14px;padding:5px;" width="100%" border="1">
						<tr>
							<td style="text-align:center;"><span>Supplier</span></td>
							<td style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
						<tr>
							<td>
							<?php echo $row->supplier_name ;?>
							</td>
							<td>
								<?php echo $row->supplier_address ;?>
							</td>
						</tr>
					</table>				
				</div>
			</div>
			<br />
         <div class="porlets-content">
            <div class="table-responsive">
                <table style="font-size:14px;!font-family:tahoma;" border="1" width="100%" class="table table-hover font_sml nila">
                  <thead>
                    <tr>
                      <td style="padding:5px;">SL#</td>
                      <td style="padding:5px;">Product Description</td>
                      <td style="padding:5px;">Unit Price</td>
                      <td style="padding:5px;">Quantity</td>
                      <td style="padding:5px;">Total</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $i = 1 ;
                    foreach ($result as  $value) { ?>
                    <tr class="">
                      <td style="padding:5px;"><?php echo $i++ ;?></td>
                      <td style="padding:5px;"><?php echo $value->product_name ;?></td>
                      <td style="padding:5px;"><?php echo $value->purchase_price ;?></td>
                      <td style="padding:5px;"><?php echo $value->total_quantity ;?></td>
                      <td style="padding:5px;"><?php echo $value->total_price ;?></td> 
                    </tr>
                    <?php } ?>
                   
                    <tr class="">
                      <td style="border-left:1px solid #fff;border-bottom:1px solid #fff;padding:5px;" colspan="4" align="right">Net Payable:</td>
                      <td style="padding:5px;" align="right"><?php echo $row->total_price;?></td>
                    </tr>
                     <tr class="">
                      <td style="border-left:1px solid #fff;border-bottom:1px solid #fff;padding:5px;" colspan="4" align="right">Payment:</td>
                      <td style="padding:5px;" align="right"><?php echo $row->total_payment;?></td>
                    </tr>
                     <tr class="">
                      <td style="border-left:1px solid #fff;border-bottom:1px solid #fff;padding:5px;" colspan="4" align="right">Due:</td>
                      <td style="padding:5px;" align="right"><?php echo number_format($row->total_price - $row->total_payment,2);?></td>
                    </tr>

                </tbody>
                </table>
                <br>
  <p>In-word: <span style="font-weight:bold;">
  <?php 
   $number = $row->total_price; 
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  echo $result . "Taka Only" ;
 ?>
</span></p>

                <!--price end-->
				<div class="fixed">
					<div class="siam">
						<ul style="list-style:none;"> 
							<li style="border-top:1px solid #000;">Prepared by</li>
							<li style="border-top:1px solid #000;margin-left:70%;">Authorized by</li>
						</ul>
					</div>
					<br/><br/><br/><br/><br/>
					<div style="border-bottom:1px solid #000;border-top:1px solid #000;width:100%;margin-top:4px;!border:1px solid #333;font-size:14px;padding:5px;">
						
					   <span style="text-align:center;"><a style="text-decoration:none;font-family:tahoma;" href="<?php //echo $row['website']; ?>" target="_blank"><?php //echo $row['website']; ?></a></span>
						Developed by: ASIAN IT INC</center> 
					</div>
				</div>
              </div><!--/table-responsive-->
            </div><!--/porlets-content-->
            
            
          </div><!--/block-web--> 
        </div><!--/col-md-12--> 
      </div><!--/row-->
	</div>
</body>
</html>	  
      <!--inovice print-->
	<script type="text/javascript">
	window.print();
	</script>	  