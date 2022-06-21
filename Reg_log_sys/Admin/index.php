<?php
session_start();

function checkUser($session){
    if(!isset($session['user'] && !$session['user']->role === "admin") 
       header("location:../login.php",true);
       return "";
    return "<h1>Welcome ".ucfirst($session['user']->name)."</h1>";
    
}

echo checkUser($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes taches</title>
</head>
<body>
    <form action="" method="post">
         <div class="container mt-3">
            <button name="logout" type="submit">Logout</button>

         </div>
    </form>
    <?php
    if(isset($_POST['logout'])){
        session_destroy();
        unset($_SESSION['user']);
        header("location:../login.php",true);
        die("");   
    }
    ?>
</body>
</html>
