<?php
/***************************************************************************************
**                                                                                    **
**  GBM - GuildBankManager                                                            **
**  Author: Steven M.                                                                 **
**                                                                                    **
**  Datei: config.php                                                                 **
**                                                                                    **
**  Hier koennen die Einstellungen zur Datenbank sowie das Administrator-Passwort     **
**  festgelegt werden. Diese Einstellungen sind unbedingt vor einer Installation      **
**  der SQL-Daten richtig vorzunehmen. Ansonsten kann keine gueltige Verbindung zum   **
**  Datenbank-Server hergestellt werden!                                              **
**                                                                                    **
***************************************************************************************/

// Zeitzone einstellen
date_default_timezone_set('Europe/Berlin');
// Konstanten / Einstellungen
$stylenum = "0";                         //  0=dark_classic 1=light_classic <style#.css>
$admin_passwd = "passwort";
$dbhost = "localhost";
$dbuser = "dbuser";
$dbpasswd = "passwort";
$databasename = "database";
$tableprefix = "gbm_";
$use_passwd = true;
$inventory_passwd = "passwort";
?>
