<?php

$conn = pg_connect("host=localhost port=5432 dbname=agricare_db user=postgres password=your_password");

if (!$conn) {
    die("Connection Failed");
}

$apiKey = "YOUR_API_KEY";

$offset = 0;
$limit  = 100;
$totalInserted = 0;

while (true) {

    $url = "https://api.data.gov.in/resource/9ef84268-d588-465a-a308-a864a43d0070"
         . "?api-key=$apiKey&format=json&limit=$limit&offset=$offset";

    $json = file_get_contents($url);
    $data = json_decode($json, true);

    if (!isset($data['records']) || count($data['records']) == 0) {
        break;
    }

    foreach ($data['records'] as $row) {

        if (strtolower($row['state']) !== 'gujarat')
            continue;

        $state = $row['state'];
        $district = $row['district'];
        $market = $row['market'];
        $commodity = $row['commodity'];
        $variety = $row['variety'];
        $grade = $row['grade'];
        $arrival_date = $row['arrival_date'];
        $min_price = (int)$row['min_price'];
        $max_price = (int)$row['max_price'];
        $modal_price = (int)$row['modal_price'];

        $query = "INSERT INTO markets 
        (state, district, market, commodity, variety, grade, arrival_date, min_price, max_price, modal_price)
        VALUES 
        ('$state', '$district', '$market', '$commodity', '$variety', '$grade', '$arrival_date', $min_price, $max_price, $modal_price)";

        pg_query($conn, $query);
        $totalInserted++;
    }

    echo "Offset $offset processed<br>";
    $offset += $limit;
    sleep(1);
}

echo "<h2>Total Gujarat Records Inserted: $totalInserted</h2>";