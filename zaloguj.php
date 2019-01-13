<?php 
require_once 'core/init.php';

if (Session::exists('user')) {
  Redirect::to('index.php');
} 

if (Input::exists()) {

        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validation->passed()) {
            $user = new User();
            $login = $user->login(strtolower(Input::get('username')), Input::get('password'));

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo 'Sorry, logging in failed.';
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    
}
 ?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="icons/css/ionicons.css">
  <title>Płatności domowe v2 (RWD)</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

  <div class="container">

    <!-- /header -->
    <header>

      <div class="main-header zaloguj">
        <div class="logo">
          <a href="index.php"><i class="ion-heart ico-serce"></i>nasze płatności - logowanie</a>
        </div>
      </div>


      
    </header>
    <!-- /header -->
    
    <!-- content -->
    <div class="content">
      

      <div class="edytuj-forms">
        
        <!-- formularze -->
        <div class="box-zaloguj">
        

            <form action="" method="post">

                <div class="nowa-box">
                    <div class="zaloguj-form">
                        <div class="form-label-zaloguj">
                            <label for="name">Login</label>
                        </div>
                        <div class="form-input-zaloguj mb-20">
                            <input type="text" name="username" value="adam" required autocomplete="off">
                        </div>
                    </div>
                

                    <div class="form-label-zaloguj">
                        <label for="name">Hasło</label>
                    </div>

                    <div class="form-input-zaloguj mb-20">
                        <input type="password" name="password" value="adam" required autocomplete="off">
                    </div>
                

                    <div class="zaloguj-btn">
                        <button type="submit" class="btn btn-green">Zaloguj</button>
                    </div>
                
                </div>

            </form>

        </div>

            <!-- /formularze -->
        </div>

        <div class="box-demo">
          <h6>Dane do logowania:</h6>
        </div>


    </div>
    <!-- /content -->

  </div>
  <!-- /container -->

  <!-- footer -->
  <footer>
    &reg; Zrealizowane z <i class="ion-heart ico-footer"></i> przez <a href="http://studiokam.pl" target="_blank">Studiokam</a>.
  </footer>
  <!-- /footer -->


</body>
</html>
