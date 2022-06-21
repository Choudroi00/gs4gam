
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
    <title>Gs-Login</title>
</head>
<body>
<div  id="container"  class="container mt-3">
<h1 style="text-align: center;">Login</h1>
<form action="" method="post">
Email:<input class="form-control" placeholder="Exmaple@email.com"type="email" name="email" id="email" required>
<br>
Password:<input class="form-control" placeholder="******" type="password" name="password" id="password" required>
<br>
<button class="btn btn-success" name="login" type="submit">Login</button>
<a class="btn btn-dark" href="register.php">register now</a>
</form>
<?php
if(isset($_POST['login'])){
   require 'dbconnect.php' ;

   $login=$db->prepare("SELECT * FROM users WHERE email= :email AND password= :password");
   $login->bindParam("email",$_POST['email']);
   $login->bindParam("password",md5($_POST['password']));
   $login->execute();

   if($login->rowCount()===1){
$user=$login->fetchObject();
if($user->activated === 1){
   session_start();
echo"Welcome ".ucfirst($user->name);
$_SESSION['user']=$user;

if($user->role === "user"){
   header("location:User/index.php",true);
}elseif($user->role === "admin"){
   header("location:Admin/index.php",true);
}elseif($user->role === "sadmin"){
   header("location:S_Admin/index.php",true);
}
}else{
    echo'<div class="alert alert-warning" role="alert">Hello '.ucfirst($user->name).', Please wait for your account validation </div>';
}
   }else{
    echo'<div class="alert alert-danger" role="alert">
    Wrong email or password 
  </div>';
   }
}

?>
<style>
    #container{
    border: 2px dotted black;
    border-radius: 15px;
    padding: 20px;
    width:30%;
}

</style>
</div>
</body>
</html>
