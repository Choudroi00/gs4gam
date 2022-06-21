
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    <title>Gs-register</title>
</head>
<body>
<div  id="container"  class="container mt-3">
    <h1 style="text-align: center;">Register now</h1>
<form action="" method="post">
    Name: <input class="form-control"type="text" name="name"required>
    <br>
    Age: <input class="form-control" type="date" name="age"required>
    <br>
    Email: <input class="form-control" type="email" name="email"required>
    <br>
    Password: <input class="form-control" type="password" name="password"required>
    <br>
    <button class="btn btn-success" name="register" type="submit">Register</button>
    <a href="login.php">Log-in</a>
</form>
</div>
 <?php

include('dbconnect.php');

if(isset($_POST['register'])){

$checkEmail=$db->prepare("SELECT * FROM users WHERE email = :email");
$email= $_POST['email'];
$checkEmail->bindParam("email",$email);
$checkEmail->execute();
    
    if($checkEmail->rowCount()>0){
        echo'</div>
        <div class="alert alert-danger" role="alert">
        Email Address is Already Registered!
        </div>';
    }else{
         $name=$_POST["name"];
         $password=$_POST["password"];
         $email=$_POST["email"];
         $age=$_POST["age"];

         $addUser = $db->prepare("INSERT INTO users (ID, name, age, email, password, created_at,activated,role) VALUES(NULL, :name, :age, :email,:password,  current_timestamp(),1,'user')");

            $addUser->bindParam("name",$name);
            $addUser->bindParam("age",$age);
            $addUser->bindParam("email",$email);
            $addUser->bindParam("password",$password);
          
            $password=md5($password);
            if($addUser->execute()){
        echo'<div class="alert alert-success" role="alert">
        Your account has been created
      </div>';
    
    }else{
       echo' <div class="alert alert-danger" role="alert">
        error try again!
        </div>';
    }

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
</body>
</html>
