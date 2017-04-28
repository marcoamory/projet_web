<section id="login">
  <?php if(isset($_GET['message']) AND $_GET['message'] == "logout"){ ?>
  <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> Vous vous êtes correctement déconnecté ! </p>
  </div>
  <?php
} ?>
<div class="jumbotron">
  <form class="form-inline" method="post" action="index.php?action=login">
  <div class="form-group">
    <label class="sr-only" for="exampleInputEmail3">Email address</label>
    <input type="email" class="form-control" id="emailLogin" name="emailLogin" placeholder="Email">
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="rememberMe"> Remember me
    </label>
  </div>
  <button type="submit" class="btn btn-success">Log in</button>
</form>
</div>
</section>