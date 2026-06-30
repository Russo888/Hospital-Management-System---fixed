<?php

// MODIFICA QUESTO VALORE con il dominio reale dell'applicazione.
if (!defined('APP_BASE_URL')) {
    define('APP_BASE_URL', 'https://localhost'); // niente slash finale
}

function check_login()
{
    // Aggiungiamo un controllo più robusto sull'esistenza della variabile
    if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0)
    {	
        $host = APP_BASE_URL;

        // Sanificazione dell'URI (path applicativo, non controllato dal client
        // in modo arbitrario: deriva dal percorso fisico dello script)
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $uri = filter_var($uri, FILTER_SANITIZE_URL);
        
        $extra = "user-login.php";
        
        // Costruzione dell'URL: host ora è un valore trusted, non più tainted
        $url = "$host$uri/$extra";
        
        // Mitigazione difesa-in-profondità contro HTTP Response Splitting (rimozione CRLF)
        $url = str_replace(array("\r", "\n"), '', $url);
        
        // Esecuzione del redirect sicuro
        header("Location: $url");
        
        // Blocco dell'esecuzione
        exit(); 
    }
}
?>
