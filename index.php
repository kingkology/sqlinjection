<?php 
session_start();

$dt=new DateTime('now', new DateTimezone('Africa/Accra'));
$ladate = $dt->format('Y-m-d');
$lamonth = $dt->format('m');
$latodayz = $ladate;
$ladatez = $dt->format('Y-m-d H:i:s');
$latimez = $dt->format('H:i:s');


?>
<html lang="en" class="no-js" oncontextmenu="return false">

<head>
	<meta charset="utf-8">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon_io/favicon.ico">
	<link rel="icon" type="image/ico" href="assets/img/favicon_io/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>SQL INJECTION TUTORIAL</title>
	<!-- login assets -->
	<link rel="stylesheet" href="login/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="login/assets/css/style.css">


	<!-- General assets -->
	<link rel="stylesheet" href="assets/libraries/animate/animate.min.css"></script>
	<link rel="stylesheet" href="assets/libraries/font-awesome-4.7.0/css/font-awesome.min.css"></script>



</head>



<?php 
if (isset($_SESSION['backend_message'])) 
{
  ?>
  <body id="bodys" onload="Swal.fire('', '<?php echo $_SESSION['backend_message']; ?>', '<?php echo $_SESSION['message_type']; ?>');" style="background-image: url(assets/img/login-bg.jpg);background-position: center center; background-size: cover;min-height: 100%;background-repeat: no-repeat;background-attachment: fixed;overflow-x:hidden;">
  <?php
  unset($_SESSION['message_type']);
  unset($_SESSION['backend_message']);
  session_unset(); 
  session_destroy();
  /*session_unset(); */
} 
else 
{
?>
<body id="bodys" style="background-image: url(assets/img/login-bg.jpg);background-position: center center; background-size: cover;min-height: 100%;background-repeat: no-repeat;background-attachment: fixed;overflow-x:hidden;">
<?php
}
?>





	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<div class="login-page" id="logit">
		<div class="form-content">
			
			<div class="container_login">
				<div class="row">
					

					<div class="col-md-1 col-sm-12 col-l-1 "></div>

					<!-- secured login -->
					<div class="col-md-5 col-sm-12 col-l-5 " style="margin: 10px;">
						
						<div class="well row pt-2x pb-3x bk-light animate__bounceIn" style="box-shadow:2px 2px 2px 2px">

							<center>
								<img src="assets/img/gimpa_logo.png" style="width:20%;height:20%;">
							</center>
							<h1 class="text-center text-bold mt-1x" style="color:black;">Exams Result Checker</h1>

							<h2 class="text-center text-bold mt-1x" style="color:green;">Secure Login</h2>
							
							<div class="col-md-8 col-md-offset-2">
								<form action="" class="mt" id="login_form">

									<input type="text" name="stopit" autocomplete="off" hidden>
									
									<label for="" class="text-uppercase text-sm animate__animated  animate__bounceIn">Ghana Card Pin</label>
									<input type="text" class="form-control mb animate__animated  animate__bounceIn" minlength="15" maxlength="15" pattern="GHA-+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+-[0-9]" title="Enter your pin in the format provided 'GHA-XXXXXXXXX-X' " id="nid" name="nid" placeholder="GHA-XXXXXXXXX-X" value="GHA-" autocomplete="off" required>

									<label for="" class="text-uppercase text-sm animate__bounceIn">Password</label>
									<input type="password" placeholder="Password" class="form-control mb animate__bounceIn" id="pword" name="pword">

									    <a href="#" class="login-box hover-glow-blue btn btn-info btn-block animate__animated animate__bounce" style="border:black solid;"  href="javascript:void(0);" onclick="login('login/apis/controllers/access/login.php','nid','pword')" id="log_button">
									    	<span></span>
									    	<span></span>
									    	<span></span>
									    	<span></span>
									      <i class="fa fa-lock"></i> Login
									    </a>
									    
									
									<br>
								</form>
							</div>
						</div>

					</div>


					<!-- Unsecured login -->
					<div class="col-md-5 col-sm-12 col-l-5 " style="margin: 10px;">
						
						<div class="well row pt-2x pb-3x bk-light animate__bounceIn" style="box-shadow:2px 2px 2px 2px">

							<center>
								<img src="assets/img/gimpa_logo.png" style="width:20%;height:20%;">
							</center>
							<h1 class="text-center text-bold mt-1x" style="color:black;">Exams Result Checker</h1>

							<h2 class="text-center text-bold mt-1x" style="color:red;">Unsecure Login</h2>
							
							<div class="col-md-8 col-md-offset-2">
								<form action="" class="mt" id="login_form2">

									<input type="text" name="stopit2" autocomplete="off" hidden>
									
									<label for="" class="text-uppercase text-sm animate__animated  animate__bounceIn">Ghana Card Pin</label>
									<input type="text" class="form-control mb animate__animated  animate__bounceIn" title="Enter your pin in the format provided 'GHA-XXXXXXXXX-X' " id="nid2" name="nid2" placeholder="GHA-XXXXXXXXX-X" value="GHA-" autocomplete="off" required>

									<label for="" class="text-uppercase text-sm animate__bounceIn">Password</label>
									<input type="password" placeholder="Password" class="form-control mb animate__bounceIn" id="pword2" name="pword2">

									    <a href="#" class="login-box hover-glow-red btn btn-danger btn-block animate__animated animate__bounce" style="border:black solid;"  href="javascript:void(0);" onclick="login_unsecure('login/apis/controllers/access/login_unsecure.php','nid2','pword2')" id="log_button2">
									    	<span></span>
									    	<span></span>
									    	<span></span>
									    	<span></span>
									      <i class="fa fa-lock"></i> Login
									    </a>
									   
									<br>
								</form>
							</div>
						</div>

					</div>

					<div class="col-md-1 col-sm-12 col-l-1 "></div>



					
				</div>
			</div>
		</div>
	</div>



	
	<!-- login assets -->
	<script src="login/assets/js/jquery.min.js"></script>
	<script src="login/assets/js/bootstrap.min.js"></script>
	<!-- General assets -->
	<script src="assets/libraries/jquery-3.4.0.min.js"></script>
	<script src="assets/libraries/sweetalert2/dist/sweetalert2.all.min.js"></script>
	<script src="assets/libraries/jquery.toaster.js"></script>
	<script src="assets/libraries/kingslibrary.js?v=<?php echo $latimez; ?>"></script>

<script>
		//submit page on enter press
 $("#nid").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button").click();
     }
 });

 $("#pword").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button").click();
     }
 });


 $("#nid2").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button2").click();
     }
 });

 $("#pword2").keyup(function(event) {
     if (event.keyCode === 13) {
         $("#log_button2").click();
     }
 });



</script>		


</body>

</html>