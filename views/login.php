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
      <?php if(isset($_GET['message']) AND $_GET['message'] == "error"){ ?>
      <div class="form-group has-error has-feedback">
    
        <input type="email" name='email_login' class="form-control" aria-describedby="inputError2Status" placeholder="Email" required>
        <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
        <span id="inputError2Status" class="sr-only">(error)</span>
      </div>
      <?php } else{ ?>
      <div class="form-group">
        <input type="email" class="form-control" name='email_login' placeholder="Email" required>
      </div>
      <?php } ?>
      <div class="checkbox">
        <label>
          <input type="checkbox"> Se souvenir de moi
        </label>
      </div>
      <button type="submit" class="btn btn-success" id='button_connexion'>Se connecter</button> 
    </form>
  </div>
</div>


      

</section>