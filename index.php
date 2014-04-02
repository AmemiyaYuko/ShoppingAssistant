<?php 
require("inc/header.inc");
$_SESSION["last_info"]="";
?>
<div class="demo-headline text-center">
	<h1 class="demo-logo">
    	<div class="logo"></div>
         	Shopping Assistant<br>
        <small>Your better choice</small>
    </h1>
 </div> <!-- /demo-headline -->
 <br><br><br><br>
<div class="row">
<span class="col-md-6 col-md-offset-3">
	<nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          	<a class="navbar-brand" href="#">Search </a>
        </div>
        <div class="collapse navbar-collapse">
          <form class="navbar-form navbar-center" role="search">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" size="36">
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
          </form>
        </div>
      </nav>
	</span>
</div>

<?php
require("inc/footer.inc");
?>
