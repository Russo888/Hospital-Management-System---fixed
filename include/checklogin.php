<?php
function check_login()
{
    // Aggiungiamo un controllo più robusto sull'esistenza della variabile
    if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0)
    {	
        // 1. Sanificazione dell'Host (Allow-list)
        // Permettiamo rigorosamente solo lettere, numeri, punti e trattini
        $host = $_SERVER['HTTP_HOST'];
        $host = preg_replace('/[^a-zA-Z0-9.-]/', '', $host);
        
        // 2. Sanificazione dell'URI
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $uri = filter_var($uri, FILTER_SANITIZE_URL);
        
        $extra = "user-login.php"; // Rimosso il "./" iniziale che è ridondante
        
        // 3. Costruzione dell'URL
        $url = "http://$host$uri/$extra";
        
        // 4. Mitigazione finale contro HTTP Response Splitting (rimozione CRLF)
        $url = str_replace(array("\r", "\n"), '', $url);
        
        // 5. Esecuzione del redirect sicuro
        header("Location: $url");
        
        // 6. Blocco dell'esecuzione
        exit(); 
    }
}
?>