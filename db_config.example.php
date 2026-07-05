<?php
// File di configurazione del database.
// NON inserire questo file nella webroot del progetto.
// Posizionarlo FUORI dalla cartella pubblica, ad esempio:
//   - /etc/myhms/db_config.php        (Linux, consigliato)
//   - C:\myhms\db_config.php           (Windows)
// e referenziarlo con require_once('/etc/myhms/db_config.php').

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // sostituisci con l'utente DB reale
define('DB_PASS', '');           // sostituisci con la password DB reale
define('DB_NAME', 'myhmsdb_final');
