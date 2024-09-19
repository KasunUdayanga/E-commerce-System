<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Doctor</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
<?php
session_start();

if(isset($_SESSION["user"])) {
    if($_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php"); // Redirect to login page if user is not authenticated or not an admin
    }
} else {
    header("location: ../login.php"); // Redirect to login page if no session exists
}

// Import database connection
include("../connection.php");

if($_POST) { // If the form is submitted
    $result = $database->query("select * from webuser");
    $name = $_POST['name'];
    $nic = $_POST['nic'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    
    // Check if the passwords match
    if ($password == $cpassword) {
        $error = '3'; // Default error code if email already exists
        $result = $database->query("select * from webuser where email='$email';");
        
        // If email is already in use, set error to 1
        if ($result->num_rows == 1) {
            $error = '1';
        } else {
            // Insert doctor information and user role into the database
            $sql1 = "insert into doctor(docemail,docname,docpassword,docnic,doctel,specialties) values('$email','$name','$password','$nic','$tele',$spec);";
            $sql2 = "insert into webuser values('$email','d')";
            $database->query($sql1); // Insert doctor record
            $database->query($sql2); // Insert webuser record
            $error = '4'; // Successfully added doctor
        }
    } else {
        $error = '2'; // Error code 2: passwords do not match
    }
} else {
    $error = '3'; // Default error if POST data is not received
}

// Redirect to doctors.php with the error code
header("location: doctors.php?action=add&error=".$error);
?>

    
   

</body>
</html>