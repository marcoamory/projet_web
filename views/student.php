﻿<section id="bloc_manager" class="container-fluid">
<?php
 	if(isset($message_warning) AND !empty($message_warning)){ ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 <p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> <?php echo $message_warning; ?> ! </p>
	</div>
<?php } ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-title text-center">
				<h4>Présences aux cours</h4>
			</div>
		</div>
	 	<div class="panel-body">
	    	<form method="post" action="index.php?action=student">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name='lesson_fk' class="form-control">
		  				<?php for($i = 0; $i < count($lesson_array) ; $i++){?>
		  					<option value="<?php echo $lesson_array[$i]->lesson_code?>"><?php echo $lesson_array[$i]->name?></option>
		  				<?php }?>
	  				</select>
	    		</div>
	    		<div class="col-md-1">
	    			<input type=submit class="btn btn-success" value="Rechercher"/>
	    		</div>
	    	</form>
	  </div>
		<?php if(isset($_SESSION['lesson_chosen'])&&isset($session_array)){?>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo $_SESSION['lesson_chosen']->name?>
						<?php for ($i=0;$i<count($week_array);$i++) {?>
						<th><?php echo "S".$week_array[$i]->week_number?></th>
						<?php }?>
					</tr>
				</thead>
				<tbody>
					<?php for($i = 0; $i < count($session_array) ; $i++){?>
					<tr>
						<td><?php echo $session_array[$i]->name?></td>
						<?php for($j = 0; $j < count($presence_array[$i]) ; $j++){?>
							<td><?php if(isset($presence_array[$i][$j]->grade)) echo $presence_array[$i][$j]->grade;
									  else echo $presence_array[$i][$j]->state;?></td>
						<?php }?>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
		<?php }?>
	</div>
</section>