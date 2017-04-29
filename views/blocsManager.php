<section class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Introduire UE/AA</h3>
				</div>
				<div class="panel-body">
					<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
						<?php if(isset($_SESSION['responsibility']) AND ($_SESSION['responsibility'] == 'blocs')){ ?>
							<h6>Bloc numéro :</h6> 
							<input type="text" name="blocNumber" placeholder="numéro bloc" />
						<?php }?>
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input class="center-block upload" type="file" name="lessons_csv" />
						<input type="submit" value="Envoyer programmeX.csv" />
					</form>
		  		</div>
			</div>
		</div>
	</div>
</section>
<?php 
	if(!empty($_SESSION['notificationSuccess'])){?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notificationSuccess']?> ! </p>
		 </div>
	<?php }
	elseif(!empty($_SESSION['notificationError'])){?>
    	<div class="alert alert-danger alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notificationError']?> ! </p>
    	</div>
<?php }?>