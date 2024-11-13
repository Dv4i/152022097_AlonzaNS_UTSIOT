<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'iot';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mengambil nilai maksimum dan minimum suhu
    $stmt = $pdo->query("SELECT MAX(suhu) AS suhumax, MIN(suhu) AS suhumax, AVG(suhu) AS suhurata FROM tb_cuaca");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $suhumax = $result['suhumax'];
    $suhumin = $result['suhumin'];
    $suhurata = round($result['suhurata'], 2);

    // Mengambil data suhu dan kelembaban maksimum dengan timestamp terkait
    $stmt = $pdo->query("SELECT id AS idx, suhu, humid, lux, ts FROM tb_cuaca WHERE suhu = $suhumax AND humid = (SELECT MAX(humid) FROM tb_cuaca)");
    $nilai_suhu_max_humid_max = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Menyusun data dalam format JSON
    $data = [
        "suhumax" => $suhumax,
        "suhumin" => $suhumin,
        "suhurata" => $suhurata,
        "nilai_suhu_max_humid_max" => $nilai_suhu_max_humid_max,
    ];

    // Mengatur header untuk JSON
    header('Content-Type: application/json');

    // Menampilkan hasil JSON
    echo json_encode($data, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
