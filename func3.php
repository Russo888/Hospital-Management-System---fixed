<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$con=mysqli_connect("localhost","root","","myhmsdb_final");
if(isset($_POST['adsub'])){
	$username=$_POST['username1'];
    $password=$_POST['password2'];

    // Cerchiamo l'utente solo per username, poi verifichiamo la password con password_verify()
    $query = "select * from admintb where username=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result)==1)
	{
		$row = mysqli_fetch_assoc($result);
		// Verifica la password contro l'hash bcrypt salvato nel DB o plain text per compatibilità
		if(password_verify($password, $row['password']) || $password === $row['password']){
			$_SESSION['username']=$username;
			header("Location:admin-panel1.php");
			exit();
		}
	}
	// Credenziali non valide
	echo("<script>alert('Invalid Username or Password. Try Again!');
          window.location.href = 'index.php';</script>");
}
if(isset($_POST['update_data']))
{
	$contact=$_POST['contact'];
    $status=$_POST['status'];

    // MODIFICA: Utilizzo del prepared statement per l'update sicuro del pagamento
    $query = "update appointmenttb set payment=? where contact=?";
    $stmt = mysqli_prepare($con, $query);
    
    // "ss" indica due stringhe: la prima per il segnaposto di payment ($status) e la seconda per contact ($contact)
    mysqli_stmt_bind_param($stmt, "ss", $status, $contact);
    
    // Eseguiamo lo statement e assegniamo l'esito a $result per il redirect successivo
    $result = mysqli_stmt_execute($stmt);

    if($result)
		header("Location:updated.php");
}




function display_docs()
{
	global $con;
	$query="select * from doctb";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
	$name=$row['name'];
	# echo'<option value="" disabled selected>Select Doctor</option>';

	// MODIFICA: Sanitizzazione del nome del medico per prevenire XSS
	$safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
	echo '<option value="' . $safe_name . '">' . $safe_name . '</option>';

	}
}

if(isset($_POST['doc_sub']))
{
    $name=$_POST['name'];

    // MODIFICA: Utilizzo del prepared statement per l'inserimento sicuro del nuovo medico
    $query = "insert into doctb(name) values (?)";
    $stmt = mysqli_prepare($con, $query);
    
    // "s" indica che ci aspettiamo un singolo parametro di tipo stringa ($name)
    mysqli_stmt_bind_param($stmt, "s", $name);
    
    // Eseguiamo lo statement e salviamo l'esito in $result per il redirect
    $result = mysqli_stmt_execute($stmt);

    if($result)
		header("Location:adddoc.php");
}
