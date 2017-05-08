<section id="bloc_manager" class="container-fluid">
	<div class="panel panel-primary">
		
		<?php if(isset($_SESSION['lesson_chosen'])){?>
		<div class="panel-heading">
			<h3 class="panel-title text-center"><?php echo $_SESSION['lesson_chosen']?></h3>
		</div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<?php for ($i=0;$i<count($week_array);$i++) {?>
						<th><?php echo "S".$week_array[$i]->week_number?></th>
						<?php }?>
					</tr>
				</thead>
				<tbody>
				<?php for ($i=0;$i<count($week_array);$i++) {?>
					<tr>
						<td>Présences</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<?php }?>
	</div>
</section>