<section class="container-fluid blocManagement">
	<div class="text-center">
		<form enctype="multipart/form-data" action="index.php?action=blocManagement" method="post">
			<fieldset>Introduire UE/AA :
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="fic" />
			<input type="submit" value="Envoyer programmeX.csv" />
			</fieldset>
		</form>
		<form enctype="multipart/form-data" action="index.php?action=blocManagement" method="post">
			<fieldset>Introduire Etudiants :
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="fic" />
			<input type="submit" value="Envoyer Etudiants.csv" />
			</fieldset>
		</form>
	</div>
</section>