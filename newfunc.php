<?php
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if (!$con) {
  die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update_data'])) {
  $contact = $_POST['contact'];
  $status = $_POST['status'];

  $query = "UPDATE appointmenttb SET payment = ? WHERE contact = ?";
  $stmt = mysqli_prepare($con, $query);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $status, $contact);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
      header("Location:updated.php");
      exit();
    }
  }
}

function display_specs() {
  global $con;

  $query = "SELECT DISTINCT spec FROM doctb";
  $result = mysqli_query($con, $query);

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $spec = htmlspecialchars($row['spec'], ENT_QUOTES, 'UTF-8');
    echo '<option data-value="' . $spec . '">' . $spec . '</option>';
  }
}

function display_docs() {
  global $con;

  $query = "SELECT username, docFees, spec FROM doctb";
  $result = mysqli_query($con, $query);

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($row['docFees'], ENT_QUOTES, 'UTF-8');
    $spec = htmlspecialchars($row['spec'], ENT_QUOTES, 'UTF-8');

    echo '<option value="' . $username . '" data-value="' . $price . '" data-spec="' . $spec . '">' . $username . '</option>';
  }
}

if (isset($_POST['doc_sub'])) {
  $username = $_POST['username'];

  $query = "INSERT INTO doctb(username) VALUES (?)";
  $stmt = mysqli_prepare($con, $query);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
      header("Location:adddoc.php");
      exit();
    }
  }
}
?>