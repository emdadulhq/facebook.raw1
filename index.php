<?php require_once "apps/autoload.php";

//logout process
if (isset($_SESSION['id']) AND $_SESSION['name']){

    header('location:profile.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Facebook - log in or sign up</title>

	<!-- CSS FILES -->
	<link rel="stylesheet" href="assets/fonts/font-awesome/css/all.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

	<!-- FAVICON  -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/fav.png">
</head>
<body>

	<!-- DESIGN FOR MOBILE -->
	<div class="facebook-mobile-design">
		
	</div>
	<!-- DESIGN FOR MOBILE -->

    <?php
    if (isset($_POST['login'])){
        $eorp = $_POST['eorp'];
        $paswrd = $_POST['pass'];

        if (empty($eorp) || empty($paswrd)){
            $masg = msgvld( 'All fields are required !!');
        }else {
            //login request send
            $qry = "SELECT * FROM users WHERE email='$eorp' || phone='$eorp'";
            $data = $con -> query($qry);
            $usr_num = $data-> num_rows;
            $login_usr_data = $data -> fetch_assoc();

            //email or phone check for login
            if($usr_num == 1){
                //password check for login

                // Password Check
                if ( password_verify( $paswrd , $login_usr_data['password']) == true ) {
                    // Redirect profile page

                    $_SESSION['id'] = $login_usr_data ['id'];
                    $_SESSION['fname'] = $login_usr_data ['fname'];
                    $_SESSION['lname'] = $login_usr_data ['lname'];
                    $_SESSION['name'] = $login_usr_data ['fname'] . ' ' . $login_usr_data ['lname'];
                    $_SESSION['email'] = $login_usr_data ['email'];
                    $_SESSION['phone'] = $login_usr_data ['phone'];
                    $_SESSION['photo'] = $login_usr_data ['photo'];
                    $_SESSION['dob'] = $login_usr_data ['dob'];
                    $_SESSION['password'] = $login_usr_data ['password'];

                   header('location:profile.php');

                }else {
                    $masg = msgvld('Wrong Password ! Enter valid password', 'warning');
                }
            }else {
                $masg = msgvld('Email or Phone number is incorrect');
            }
        }
    }
    ?>
	<!-- FACEBOOK HEADER START  -->
	<div class="facebook-header">
		<div class="container">
			<div class="logo">
				<img src="assets/img/logo.png" alt="">
			</div>
			<div class="login-area">
                <?php
                if (isset($masg)){
                    echo $masg;
                }
                ?>
				<form method="POST" action="">
					<div class="login1">
						<label for="email">Email or Phone</label>
						<input name="eorp" type="text" id="email">
					</div>
					<div class="login2">
						<label for="pass">Password</label>
						<input name="pass" type="password" id="pass">
						<a href="#">Forgotten account?</a>
					</div>
					<div class="login3">
						<input name="login" type="submit" value="Log In">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- FACEBOOK HEADER END  -->



    <?php
    /*
     * Form isseting
     * */
    if (isset($_POST['send'])){
        //value get
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pass_hash = password_hash( $password ,PASSWORD_DEFAULT);
        //date of birth fix
        if (isset($_POST['dob'])){
            $dob = $_POST['dob'];
        }
        //gender value fix
        if(isset($_POST['gender'])){
            $gender=$_POST['gender'];
        }




        if (empty($fname) || empty($lname) || empty($phone) ||empty($email) || empty($password)|| empty($dob)){
            $mess = msgvld('All fields are required !!','danger');
        }elseif (filter_var(FILTER_VALIDATE_EMAIL)==false){
            $mess = msgvld('Invalid Email !!','warning');
        }elseif (dbCheck($con,'users','email',$email)==false){
            $mess = msgvld('Email already exist !!','warning');
        }elseif (dbCheck($con,'users','phone',$phone)==false){
            $mess = msgvld('Phone number already exist !!','warning');
        }else{
            $photo_data = photoUpload($_FILES['photo'], 'photo/', ['jpg','png','gif','jpeg'], '500');
            $photo_msg = $photo_data ['mess'];
            $photo_name = $photo_data ['file_name'];
            if (!empty($photo_msg)){
                $mess = $photo_msg;
            }else{
                $qry= "INSERT INTO users(fname , lname , phone , email , password , dob , gender , photo) VALUES ('$fname','$lname','$phone','$email','$pass_hash','$dob','$gender','$photo_name')";
                $con ->query($qry);

                $mess = msgvld('Congratulation! you are registered !!','success');
            }

        }



    }

    ?>


    <!-- FACEBOOK BODY START  -->
	<div class="facbook-body">
		<div class="container">
			<div class="body-left">
				<h2>Facebook helps you connect and share with the people in your life.</h2>
				<img src="assets/img/OBaVg52wtTZ (1).png" alt="">
			</div>

			<div class="body-right">
                <form action="" method="POST" enctype="multipart/form-data">
                <?php
                if (isset($mess)){
                    echo $mess;
                }
                ?>
				<h1>Create an account</h1>
				<h2>It's quick and easy.</h2>
				<div class="reg-area">
					<input name="fname" type="text" placeholder="First name" value="<?php old('fname')?>">
					<input name="lname" type="text" placeholder="Surname" value="<?php old('lname')?>">
					<input name="phone" type="text" placeholder="Mobile number" value="<?php old('phone')?>">
					<input name="email" type="text" placeholder="Email address" value="<?php old('email')?>">
					<input name="password" type="password" placeholder="New password" value="<?php old('password')?>">
				</div>

                    <div class="form-group birthday">
                        <label for="">Birthday</label>
                        <input name="dob" value="<?php old('dob')?>" class="form-control" type="date">
					    <a title="Click for more information" href="#"><i class="fas fa-question-circle"></i></a>
				</div>
				<div class="gender">
					<h3>Gender</h3>
					<input name="gender" value="female" type="radio" id="female"> <label for="female">Female</label>
					<input name="gender" value="male" type="radio" id="male"> <label for="male">Male</label>
					<input name="gender" value="custom" type="radio" id="custom"> <label for="custom">Custom</label>
					<a href="#"><i class="fas fa-question-circle"></i></a>
					<p>By clicking Sign Up, you agree to our <a href="#">Terms</a>, <a href="#">Data Policy</a> and <a href="#">Cookie Policy</a>. You may receive SMS notifications from us and can opt out at any time.</p>
				</div>
				<div class="photo">
					<input name="photo" type="file">
				</div>


				<div class="signup-area">
					<input name="send" type="submit" value="Sign Up">
					<p><a href="#">Create a Page</a> for a celebrity, band or business.</p>
				</div>
                </form>
            </div>

		</div>
	</div>
	<!-- FACEBOOK BODY END  -->

	<!-- FACEBOOK FOOTER START-->
	<div class="facebook-footer">
		<div class="container">
			<div class="footer-top">
				<ul>
					<li><a href="#">English (UK)</a></li>
					<li><a href="#">বাংলা</a></li>
					<li><a href="#">অসমীয়া</a></li>
					<li><a href="#">हिन्दी</a></li>
					<li><a href="#">नेपाली</a></li>
					<li><a href="#">Bahasa Indonesia</a></li>
					<li><a href="#">العربية</a></li>
					<li><a href="#">ا中文(简体)</a></li>
					<li><a href="#">Bahasa Melayu</a></li>
					<li><a href="#">Español</a></li>
					<li><a href="#">Português (Brasil)</a></li>
					<li><a href="#"><i class="fas fa-plus"></i></a></li>
				</ul>
			</div>
			<div class="footer-middle">
				<ul>
					<li><a href="#">Sign Up</a></li>
					<li><a href="#">Log In</a></li>
					<li><a href="#">Messenger</a></li>
					<li><a href="#">Facebook Lite</a></li>
					<li><a href="#">Watch</a></li>
					<li><a href="#">People</a></li>
					<li><a href="#">Pages</a></li>
					<li><a href="#">Page categories</a></li>
					<li><a href="#">Places</a></li>
					<li><a href="#">Games</a></li>
					<li><a href="#">Locations</a></li>
					<li><a href="#">Marketplace</a></li>
					<li><a href="#">Groups</a></li>
					<li><a href="#">Instagram</a></li>
					<li><a href="#">Local</a></li>
					<li><a href="#">Fundraisers</a></li>
					<li><a href="#">Services</a></li>
					<li><a href="#">About</a></li>
					<li><a href="#">Create ad</a></li>
					<li><a href="#">Developers</a></li>
					<li><a href="#">Careers</a></li>
					<li><a href="#">Privacy</a></li>
					<li><a href="#">Cookies</a></li>
					<li><a href="#">AdChoices</a></li>
					<li><a href="#">Terms</a></li>
					<li><a href="#">Help</a></li>
					<li><a href="#">Settings</a></li>
				</ul>
			</div>
			<div class="footer-bottom">
				<p>Facebook © 2020</p>
			</div>
		</div>
	</div>
	<!-- FACEBOOK FOOTER END-->
	
















</body>
</html>