<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
      .body-item {
        display: flex;
        width: 100%;
        justify-content: start;
      }

      .body-middle-axis {
        display: flex;
        width: 1rem;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }

      .body-left-date {
        display: flex;
        width: 30%;
        align-items: flex-end;
        flex-direction: column;
        justify-content: center;
        padding: 10px; 
      }

      .online-top-closing {
        width: 1px;
        height: 3rem;
        background: #999
      }

      .dot-closing {
        width: .6rem;
        height: .6rem;
        border-radius: 50%;
        background: #fe4f33;
      }

      .online-bottom {
        width: 1px;
        height: 3rem;
        background: #999;
      }

      .body-right {
        display: flex;
        flex-grow: 1;
        flex-direction: column;
        justify-content: center;  
        padding: 10px;    
    }
	</style>
</head>

<body>
	<h1 class="wp-heading-inline">Tracking</h1>
	<hr class="wp-header-end">
	<div class="tablenav top">
	</div>
	<form method="POST">
		<div class="form-wrap" style="text-align: center">
			<input type="text" name="tracking" size="30" placeholder="Input Waybill Number" autocomplete="off" style="width: 60%">
			<button class="button-primary" style="width: 15%">Track</button>
		</div>
	</form>

	<?php if ( is_array( $res ) ) { 

		if($res['code'] == '200'){
            $value = json_decode($res['data'], true);

	?>
			<div>

				<div style="width: 100%; display: flex;">
			    	<div style=" display: flex;justify-content: space-around;flex-direction: column;align-items: flex-start;">
			        	<h3>Waybill Number : <?= $value['order']['billCode'] ?></h3>
			        	<div class='head-right-middle'>Status : <?= $value['status'] ?></div>
			        	<div class='head-right-bottom'>Dispatcher Contact : <?=  $value['details'][0]['employeePhone'] ?></div>
			      	</div>
			    </div>

			    <hr/>

			    <div class='body-container'>
			    	<?php if(!empty($value['details'])) { ?> 
				    	<?php foreach ($value['details'] as $key => $data) { ?>

				    	<div class="body-item">
							<div class='body-left-date'>
							  <div><?= date('Y-m-d H:i', strtotime($data['acceptTime'])) ?></div>
							</div>

							<div class='body-middle-axis'>
							  <div class='online-top-closing'></div>
							  <div class='dot-closing'></div>
							  <div class='online-bottom'></div>
							</div>

							<div class='body-right'>
							  <div class='body-statusing'><b><?= $data['scanstatus'] ?></b></div>
							  <div class='body-status-address'><?= $data['state'] ?></div>
							  <div class='body-status-address'>City : <?= $data['city'] ?></div>
							  <div class='body-status-address'><?= $data['remark'] ?></div>
							</div>
						</div>

						<?php } ?>
					<?php } else { ?>
						<div class='body-container'>
			                <h3 class="msg">Sorry, information not found</h3>
			                <h3 class="msg"> please check again later!!!</h3>
			            </div>
					<?php } ?>
			    </div>

			    <hr/>
			</div>

		<?php }else{ ?>

			<div style="width: 100%; display: flex;">
		    	<div style=" display: flex;justify-content: space-around;flex-direction: column;align-items: flex-start;">
		        	<h3>Waybill Number : </h3>
		      	</div>
		    </div>

		    <hr/>

            <div class='body-container'>
                <h3 class="msg">Sorry, information not found</h3>
                <h3 class="msg"> please check again later!!!</h3>
            </div>

	<?php }}else{ ?>
		<center>
			<h3><?= $res ?></h3>
		</center>
	<?php } ?>
	
</body>
</html>