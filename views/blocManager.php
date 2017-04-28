<section class="container-fluid">
	<div class="text-center">
		<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
			<fieldset>Introduire UE/AA :
			<?php if(isset($_SESSION['responsibility']) AND ($_SESSION['responsibility'] == 'blocs')){ ?>
				<input type="text" name="blocName" placeholder="Bloc concerné" />
			<?php }?>
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="lessons_csv" />
			<input type="submit" value="Envoyer programmeX.csv" />
			</fieldset>
		</form>
		<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
			<fieldset>Introduire Etudiants :
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="students_csv" />
			<input type="submit" value="Envoyer Etudiants.csv" />
			</fieldset>
		</form>
	</div>
</section>