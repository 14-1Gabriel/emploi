<?php
session_start();

$conn = new mysqli("localhost", "root", "", "emploi_temps");

if ($conn->connect_error) {
    die("Erreur: " . $conn->connect_error);
}
?>