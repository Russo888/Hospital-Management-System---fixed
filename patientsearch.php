<!DOCTYPE html>
 <?php #include("func.php");?>
<html>
<head>
	<title>Patient Details</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css">
</head>
<body>
<?php
include("newfunc.php");
if(isset($_POST['patient_search_submit']))
{
	$contact = $_POST['patient_contact'];
	$query = "SELECT fname, lname, email, contact FROM patreg WHERE contact = ?";
  $stmt = mysqli_prepare($con, $query);

  if (!$stmt) {
    echo "<script> alert('Unable to process your request. Try again!'); 
          window.location.href = 'admin-panel1.php#list-doc';</script>";
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $contact);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);

  if(!$row){
    echo "<script> alert('No entries found! Please enter valid details'); 
          window.location.href = 'admin-panel1.php#list-doc';</script>";
  }
  else {
    echo "<div class='container-fluid' style='margin-top:50px;'>
	<div class='card'>
	<div class='card-body' style='background-color:#342ac1;color:#ffffff;'>
<table class='table table-hover'>
  <thead>
    <tr>
      <th scope='col'>First Name</th>
      <th scope='col'>Last Name</th>
      <th scope='col'>Email</th>
      <th scope='col'>Contact</th>
    </tr>
  </thead>
  <tbody>";

	
		    $fname = htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8');
        $lname = htmlspecialchars($row['lname'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
        $contact = htmlspecialchars($row['contact'], ENT_QUOTES, 'UTF-8');
        echo "<tr>
          <td>$fname</td>
          <td>$lname</td>
          <td>$email</td>
          <td>$contact</td>
        </tr>";
    
	echo "</tbody></table><center><a href='admin-panel1.php' class='btn btn-light'>Back to dashboard</a></div></center></div></div></div>";
}
  }
	
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GcoLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script> 
</body>
</html>