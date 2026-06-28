<?php 
// Imposta la modalità di report degli errori di mysqli per maggiore sicurezza (opzionale ma consigliato)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if(isset($_POST['btnSubmit']))
{
    $name = $_POST['txtName'];
    $email = $_POST['txtEmail'];
    $contact = $_POST['txtPhone'];
    $message = $_POST['txtMsg'];

    // 1. Definiamo la query INSERT con i placeholder (?) per ogni valore
    $query = "INSERT INTO contact(name, email, contact, message) VALUES (?, ?, ?, ?)";
    
    // 2. Prepariamo lo statement
    $stmt = mysqli_prepare($con, $query);
    
    // 3. Leghiamo i parametri. "ssss" indica che stiamo passando 4 variabili di tipo Stringa
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $contact, $message);
    
    // 4. Eseguiamo la query
    $result = mysqli_stmt_execute($stmt);
    
    if($result)
    {
        echo '<script type="text/javascript">'; 
        echo 'alert("Message sent successfully!");'; 
        echo 'window.location.href = "contact.html";';
        echo '</script>';
    }
    
    // 5. Buona pratica: chiudiamo lo statement per liberare le risorse
    mysqli_stmt_close($stmt);
}
?>