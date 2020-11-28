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
      <center><h3><span style="font-family:tahoma;border:1px solid #000;padding-top:4px;padding-bottom:4px;padding-left:27px;padding-right:27px;">INCOME STATEMENT REPORT </span></h3></center>      
      <div class="row">
          <table style="font-size:12px;">
                                    <tr>
                                        <td><strong>INCOME STATEMENT REPORT BETWEEN FROM <?php echo date('d M Y',strtotime($from_date)) ;?> TO <?php echo date('d M Y',strtotime($to_date)) ; ?>
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
                                                <th class="nila">Date</th>
                                                <th class="nila">Purpose</th>
                                                <th class="nila">Earn</th>
                                                <th class="nila">Cost</th>
                                                </tr>
  </thead>
  <tbody>
                                            <?php
                                            $i = 1 ; 
                                            $total_earn = 0 ;
                                            $total_cost = 0 ;
                                            foreach ($result as $value) { 
                                              $total_earn = $total_earn + $value->profit_earn ;
                                              $total_cost = $total_cost + $value->profit_cost ;
                                              ?>
                                              <?php if($value->profit_earn == 0 AND $value->profit_cost == 0):?>
                                              <?php else:?>
                                            <tr>
                                                <td class="nila"><?php echo  $i++ ; ?></td>
                                                <td class="nila"><?php echo date('d-M-Y',strtotime($value->created_at)) ; ?></td>
                                                <td class="nila"><?php echo $value->purpose; ?></td>                                              
                                                <td class="nila"><?php echo $value->profit_earn; ?></td>
                                                <td class="nila"><?php 
                                                echo $value->profit_cost;?></td>
                                            </tr>
                                          <?php endif; ?>
                                            <?php } ?>
                                                                   
                                        </tbody>
                                           
                                                <tr>
                                                   <td colspan="3" class="nila"><strong>TOTAL</strong></td>
                                                   <td class="nila">
                                                    <strong>
                                                       <?php
                                                       echo number_format($total_earn,2);
                                                       ?>
                                                   </strong>

                                                   </td>
                                                   <td class="nila">
                                                     <strong>
                                                       <?php
                                                       echo number_format($total_cost,2);
                                                       ?>
                                                           </strong>
                                                       
                                                   </td> 

                                                </tr>
                                                <tr>
                                                   <td colspan="3" class="nila"><strong>NET PROFIT</strong></td>
                                                   <td colspan="2" class="nila">
                                                    <?php
                                                    $total_profit = $total_earn - $total_cost ;
                                                    ?> 
                                                    <strong>
                                                    <?php if($total_profit > 0):?>
                                                        
                                                       <?php
                                                       echo number_format($total_earn - $total_cost,2);
                                                       ?>
                                                   
                                                 <?php else:?>
                                                  <span style="color:red;">
                                                   <?php
                                                       echo number_format($total_earn - $total_cost,2);
                                                       ?>
                                                     </span>
                                                 <?php endif;?>
                                                 </strong>
                                                   </td>
                                                </tr>
</tbody>
</table>
	<script type="text/javascript">
	window.print();
	</script>
    </body>
</html>

   