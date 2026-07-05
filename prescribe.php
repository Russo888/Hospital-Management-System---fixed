<!DOCTYPE html>
<?php
include('func1.php');
$pid='';
$ID='';
$appdate='';
$apptime='';
$fname = '';
$lname= '';
$doctor = $_SESSION['dname'];
$doctorSafe = htmlspecialchars($doctor, ENT_QUOTES, 'UTF-8');

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfTokenSafe = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');

function getAppointmentForPrescription($con, $pid, $ID, $doctor) {
  $stmt = mysqli_prepare($con, "SELECT fname, lname, appdate, apptime FROM appointmenttb WHERE pid = ? AND ID = ? AND doctor = ? LIMIT 1");

  if (!$stmt) {
    return null;
  }

  mysqli_stmt_bind_param($stmt, "sss", $pid, $ID, $doctor);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $appointment = mysqli_fetch_array($result, MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);

  return $appointment ?: null;
}

if(isset($_GET['pid']) && isset($_GET['ID'])) {
  $pid = $_GET['pid'];
  $ID = $_GET['ID'];
  $appointment = getAppointmentForPrescription($con, $pid, $ID, $doctor);

  if ($appointment) {
    $fname = $appointment['fname'];
    $lname = $appointment['lname'];
    $appdate = $appointment['appdate'];
    $apptime = $appointment['apptime'];
  }
  else {
    echo "<script>alert('Invalid appointment selected.'); window.location.href='doctor-panel.php';</script>";
    exit();
  }
}



if(isset($_POST['prescribe']) && isset($_POST['pid']) && isset($_POST['ID'])){
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "<script>alert('Invalid request. Please try again.'); window.location.href='doctor-panel.php';</script>";
    exit();
  }
  $pid = $_POST['pid'];
  $ID = $_POST['ID'];
  $appointment = getAppointmentForPrescription($con, $pid, $ID, $doctor);

  if (!$appointment) {
    echo "<script>alert('Invalid appointment data. Please try again.'); window.location.href='doctor-panel.php';</script>";
    exit();
  }

  $fname = $appointment['fname'];
  $lname = $appointment['lname'];
  $appdate = $appointment['appdate'];
  $apptime = $appointment['apptime'];
  $disease = $_POST['disease'];
  $allergy = $_POST['allergy'];
  $prescription = $_POST['prescription'];
  
  $stmt = mysqli_prepare($con, "INSERT INTO prestb(doctor, pid, ID, fname, lname, appdate, apptime, disease, allergy, prescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssssssss", $doctor, $pid, $ID, $fname, $lname, $appdate, $apptime, $disease, $allergy, $prescription);
    $query = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($query)
    {
      echo "<script>alert('Prescribed successfully!');</script>";
    }
    else{
      echo "<script>alert('Unable to process your request. Try again!');</script>";
    }
  }
  else{
    echo "<script>alert('Unable to prepare your request. Try again!');</script>";
  }
  // else{
  //   echo "<script>alert('GET is not working!');</script>";
  // }initial
  // enga error?
}

?>

<html lang="en">
  <head>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <meta name="viewport" content="width=device-width, -scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    
        <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <a class="navbar-brand" href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Global Hospital </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <style >
    .bg-primary {
    background: -webkit-linear-gradient(left, #3931af, #00c6ff);
}
.list-group-item.active {
    z-index: 2;
    color: #fff;
    background-color: #342ac1;
    border-color: #007bff;
}
.text-primary {
    color: #342ac1!important;
}

.btn-primary{
  background-color: #3c50c1;
  border-color: #3c50c1;
}
  </style>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav mr-auto">
       <li class="nav-item">
        <a class="nav-link" href="logout1.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
        
      </li>
       <li class="nav-item">
       <a class="nav-link" href="doctor-panel.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Back</a>
      </li>
    </ul>
  </div>
</nav>

</head>
  <style type="text/css">
    button:hover{cursor:pointer;}
    #inputbtn:hover{cursor:pointer;}
  </style>

<body style="padding-top:50px;">
   <div class="container-fluid" style="margin-top:50px;">
    <h3 style = "margin-left: 40%;  padding-bottom: 20px; font-family: 'IBM Plex Sans', sans-serif;"> Welcome &nbsp<?php echo $doctorSafe; ?>
   </h3>

   <div class="tab-pane" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">
        <form class="form-group" name="prescribeform" method="post" action="prescribe.php">
        
          <div class="row">
                  <div class="col-md-4"><label>Disease:</label></div>
                  <div class="col-md-8">
                  <!-- <input type="text" class="form-control" name="disease" required> -->
                  <textarea id="disease" cols="86" rows ="5" name="disease" required></textarea>
                  </div><br><br><br>
                  
                  <div class="col-md-4"><label>Allergies:</label></div>
                  <div class="col-md-8">
                  <!-- <input type="text"  class="form-control" name="allergy" required> -->
                  <textarea id="allergy" cols="86" rows ="5" name="allergy" required></textarea>
                  </div><br><br><br>
                  <div class="col-md-4"><label>Prescription:</label></div>
                  <div class="col-md-8">
                  <!-- <input type="text" class="form-control"  name="prescription"  required> -->
                  <textarea id="prescription" cols="86" rows ="10" name="prescription" required></textarea>
                  </div><br><br><br>
                  <input type="hidden" name="csrf_token" value="<?php echo $csrfTokenSafe; ?>" />
                  <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid, ENT_QUOTES, 'UTF-8'); ?>" />
                  <input type="hidden" name="ID" value="<?php echo htmlspecialchars($ID, ENT_QUOTES, 'UTF-8'); ?>" />
                  <br><br><br><br>
          <input type="submit" name="prescribe" value="Prescribe" class="btn btn-primary" style="margin-left: 40pc;">
          
        </form>
        <br>
        
      </div>
      </div>
      
