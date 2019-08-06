<?php
require './config.php';

// windows extension=php_ldap.dll
//centos 7: yum install php-ldap
//debian apt-get install php7.0-ldap
//https://www.zflexsoftware.com/index.php/pages/free-online-ldap

// conexión al servidor LDAP
$ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");

if ($ldapconn) {
    // realizando la autenticación
    $ldapbind = ldap_bind($ldapconn, $config['usernameConsultaLdap'], $config['passwordConsultaLdap']) or die("Error trying to bind: " . ldap_error($ldapconn));
    
    // verificación del enlace
    if ($ldapbind) {
        $serch = ldap_search($ldapconn, $config['baseSearch'], "uid=*") or die("Error in search query: " . ldap_error($ldapconn));
        
        //validamos busqueda
        if ($serch) {
            $data = ldap_get_entries($ldapconn, $serch);
            
            echo '<h1>Listado de emails</h1>';
            echo '<br>';
            
            //recorremos busqueda
            for ($i = 0; $i < count($data); $i++) {
                
                if (isset($data[$i][$config['columnaLdap']][0])) {
                    echo $data[$i][$config['columnaLdap']][0];
                    echo '<br>';
                }
            }
            
        }
    }   
    ldap_close($ldapconn);   
}
?> 
