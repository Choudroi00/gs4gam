<?php
include ('./db.php');


$letters = 'abcdefghijklmnopqrstuvwxyz';
$capLetters = strtoupper($letters) ;
$num = '123456789_';
$dic = str_split( $letters.$capLetters.$num );

print_r(json_encode(handelData(array($_POST['auth'],$_POST['username'],$_POST['password'],$_POST['email'],$_POST['phone']),$mainDB,$dic)));


function handelData($data,$db,$dic)
{
    switch ($data[0]) {
        case 'login':
            return login(array($data[1],md5($data[2]),''),$db,$dic);
            break;
        case 'register':
            return register(array($data[1],md5($data[2]),$data[3],$data[4]),$db,$dic);
            break;
        
        default:
            # code...
            break;
    }
}

/*
    @$user must be an array (userName : type string,contact :type array)
    @contact : array(email,phone) / array(email) / array(phone)
*/

function register($user,$db,$dic)
{
    $output = '';
    $sql = 'SELECT * FROM users';
    $result = mysqliResultToArray( mysqli_query($db,$sql) );
    $output = userExist($user,$result) ?array('registration'=>'filed','registrationResult' => 'username , email or phone number already used !'):'newUser';
    if($output == 'newUser')
        $output = array('registration'=>'success', 'registrationResult' =>generateAuthKey($user,createUser($user,$db),$dic));
    return $output;
}

function validateContact($contact)
{
    /*
        هنا التحقق من الايميل او الهاتف ...

    */
}

/*
 @$user : array with (username,password : md5,contact : empty)
*/

function login($user,$db,$dic)
{
    $output = '';
    $sql = 'SELECT * FROM users';
    $result = mysqliResultToArray( mysqli_query($db,$sql) );
    $output = userExist($user,$result) ?'userExist':array('login'=>'filed', 'loginResult' =>'user not exist!');
    if($output == 'userExist')
        $output = array('loginResult' => checkPassword($result,$user[1])? generateAuthKey($user,getUID($db,$user[1]),$dic) : 'ERROR:PASSWORD');
    return $output;
}

function checkPassword($row,$pass)
{
    foreach ($row as $data) {
        if($data[1] == $pass)
            return true;
    }
    return false;
}

function createUser($data,mysqli $db)
{
    $userInsert = $db ->prepare('INSERT INTO users(username,password,contact,preferences,friends,inbox,story) VALUES(?,?,?,?,?,?,?,?)');
    $userInsert ->bind_param('ssssssss',$username,$password,$contact,$pref,$friends,$inbox,$story);

    $username = $data[0];
    $password = md5($data[1]);
    $contact =json_encode( array("email"=>$data[2],"phone"=>$data[3]));
    $pref = json_encode(array(array()));
    $friends = json_encode(array(array()));
    $inbox = json_encode(array(array()));
    $story = json_encode(array(array()));

    $userInsert->execute();
    $userInsert->close();
    return getUID($db,$data[0]);
    
}

function getUID(mysqli $db,$user)
{
    
    return $db->query("SELECT id FROM users WHERE username = '$user'");
}

function userExist($given,$row)
{
    foreach ($row as $data) {
        if($data[0] === $given[0] || strpos($data[2],json_encode($given[2]))) 
            return true;
    }

    return false;
}

function mysqliResultToArray($result){
    $hostArray = array() ;
    $k = 0;
    while ($row = mysqli_fetch_array($result)) {
        $hostArray[$k] = $row;
        $k++;
    }
    return $hostArray;

}

function generateAuthKey($user,$id,$dic){
    

    $userPositions =getPositions( str_split($user),$dic);
    $idPositions =getPositions(str_split($id),$dic);

    $validityTime =getPositions(str_split( floor(microtime(true)*1000) + 24*3600*1000) ,$dic ) ;

    $encryptKey = array(7,3);
    $decryptKey = array(9,-27);

    $treatedUser = encrypt($userPositions,$encryptKey);
    $treatedid = encrypt($idPositions,$$encryptKey);
    $treatedTime = encrypt($validityTime,$encryptKey);

    return base64_encode( toChars($treatedUser,$dic).','. toChars($treatedid,$dic). ','. toChars($treatedTime,$dic) );
}

function getPositions($array,$dic){
    $positions = array();
    $k = 0;
    for ($i=0; $i <count($array) ; $i++) { 
        
        for($j = 0;$j < count($dic);$j++) {
            if($dic[$j] == $array[$i]) {
                $positions[$k] = $j;
                $k++;     
            }
        }
    }

    return $positions;

}

function encrypt($positions,$key){
    $encryptedArray = array();
    
    for ($i=0; $i < count($positions); $i++) { 
        $encryptedArray[$i] = (($positions[$i] * $key[0]) + $key[1]) % 62 > -1 ? (($positions[$i] * $key[0]) + $key[1]) % 62:((($positions[$i] * $key[0]) + $key[1]) % 62)+62;
    
    }
    return $encryptedArray;
}

function getChars($array,$dic)
{
    $char = null;
    for ($i=0; $i < count($array); $i++) { 
        for ($j=0; $j < count($dic); $j++) { 
            if($array[$i] == $j) {
                $char .= $dic[$j];
            }

        }
    }

    return $char;
}

function toChars($array,$dic)
    {
        $char = '';
        for ($i=0; $i < count($array); $i++) { 
            for ($k=0; $k < count($dic); $k++) { 
               if( $array[$i] == $k) {
                    $char .= $dic[$k];
                }
    
            }
        }
    
        return $char;
    }
