<section id="login">

      <?php if(isset($_GET['message']) AND $_GET['message'] == "logout"){ ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> Vous vous êtes correctement déconnecté ! </p>
        </div>
        <?php
      } ?>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Connexion</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="index.php?action=login">
      <div class="form-group">
        <input type="email" class="form-control" id="emailLogin" name='emailLogin' placeholder="Email">
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox"> Rester connectés
        </label>
      </div>
      <button type="submit" class="btn btn-success" id='button_connexion'>Se connecter</button> 
    </form>
  </div>
</div>


      

</section>