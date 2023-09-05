<?php


if(isset($_SESSION["userid"])==false)
{
$b="";
$a=<<<a
<!DOCTYPE html>
<html dir="rtl">
<head>
  <title>تسجيل الدخول - الارشيف الالكتروني</title>
   <link rel="shortcut icon" href="./assets/images/favicon.ico"> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assets/css/vendor.css">
  <link rel="stylesheet" type="text/css" href="assets/css/flat-admin.css">
<link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">

  <!-- Theme -->
  <link rel="stylesheet" type="text/css" href="assets/css/theme/blue-sky.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/blue.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/red.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/yellow.css">

</head>
<body>
  <div class="app app-default">
<div class="app-container app-login" style = "">
  <div class="flex-center">
    <div class="app-header"></div>
    <div class="app-body">
      <div class="loader-container text-center">
          <div class="icon">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
              </div>
            </div>
          <div class="title">تسجيل الدخول...</div>
      </div>
      <div class="app-block">
      <div class="app-form">
        <div class="form-header">
          <div class="app-brand"> تسجيل<span class="highlight"> الدخول</span></div>
        </div>
		<div class="err"></div>
        <form action="" method="POST">
            <div class="input-group">
						 <span class="input-group-addon" id="basic-addon1">
                <i class="fa fa-user" aria-hidden="true"></i></span>
              <input required type="text" class="form-control" placeholder="اسم المستخدم" name="username" style="text-align:right;" aria-describedby="basic-addon1">
            </div>
            <div class="input-group">
              			  <span class="input-group-addon" id="basic-addon2">
                <i class="fa fa-key" aria-hidden="true"></i></span>
              <input required type="password" class="form-control" style="text-align:right;" name="password" placeholder="كلمة المرور" aria-describedby="basic-addon2">
            </div>
			<div class="input-group"><span class="input-group-addon" id="basic-addon2" >
                <i class="fa fa-calendar-o" aria-hidden="true"></i></span>
            <select class="form-control" name="year" >
              <option value="2016">ارشيف 2016</option>
              <option value="2017">ارشيف 2017</option>
<option value="2018" >ارشيف 2018</option>
<option value="2019" >ارشيف 2019</option>
<option value="2020" >ارشيف 2020</option>
<option value="2021" >ارشيف 2021</option>
<option value="2022" >ارشيف 2022</option>
<option value="2023" selected>ارشيف 2023</option>
            </select>
          </div>
            <div class="text-center">
			<br>
                <input type="submit" class="btn btn-success btn-submit" value="تسجيل الدخول" name="login">
            </div>
        </form>
      </div>
      </div>
    </div>
    <div class="app-footer">
    </div>
  </div>
</div>

  </div>
  
  <script type="text/javascript" src="assets/js/vendor.js"></script>


</body>
</html>
a;
AddContent($a,'body2',0);
if(isset($_POST["login"]))
{
$name=addslashes($_POST["username"]);
$year=addslashes($_POST["year"]);
$password=addslashes(md5($_POST["password"]));
$sql=mysqli_query($con,"select id,username,usertype,photo,name from users where username='$name' and password='$password'");
echo mysqli_error($con);
if(mysqli_num_rows($sql)==1)
{
session_destroy();
session_start();
list($id,$name,$type,$photo,$nn)=mysqli_fetch_row($sql);
$_SESSION["userid"]=$id;
$_SESSION["username"]=$name;
$_SESSION["name"]=$nn;
$_SESSION["usertype"]=$type;
$_SESSION["year"]=$year;
$_SESSION["photo"]=$photo;
include("links.php");
include("main.php");
}
else
{
$b=<<<ab
<script>
$( "err" ).after( document.createTextNode( '<div class="alert alert-danger  alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            خطأ في اسم المستخدم او كلمة السر
        </div>' ) );
</script>
ab;
}
}

}
else
include("main.php");
?>