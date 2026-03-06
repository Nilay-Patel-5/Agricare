<?php

$conn = pg_connect("host=localhost port=5432 dbname=agricare_db user=postgres password=nrp@postgres7");

if (!$conn) {
    die("Connection failed");
}

echo "Connected to PostgreSQL successfully!";

?>