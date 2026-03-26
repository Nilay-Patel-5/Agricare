<?php

$conn = pg_connect("host=db.fnfqrectniyjpkyfkmal.supabase.co port=5432 dbname=postgres user=postgres password=nrpsupabase7 sslmode=require");

if (!$conn) {
    die("Connection failed");
}

echo "Connected to PostgreSQL successfully!";
