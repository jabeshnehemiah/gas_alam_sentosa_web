<?php
require_once('class/movie.php');
$mysqli = new mysqli('localhost', 'root', '', 'database');
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
$movie=new Movie();
$arr=[];
$arr['judul'] = $_POST['txtJudul'];
$arr['rilis'] = $_POST['dtpRilis'];
$arr['skor'] = $_POST['numSkor'];
$arr['sinopsis'] = $_POST['txtSinopsis'];
$arr['serial'] = $_POST['rdoSerial'];
$genres = $_POST['genre'];
$posters = $_FILES['poster'];

print_r($arr);

$idmovie = $movie->insertMovie($arr);

foreach ($genres as $genre) {
    $stmt = $mysqli->prepare("INSERT INTO genre_movie VALUES (?,?)");
    $stmt->bind_param('ii', $genre, $idmovie);
    $stmt->execute();
}

for ($i = 0; $i < count($posters['name']); $i++) {
    if ($posters['name'][$i] != "") {
        $ext = pathinfo($posters['name'][$i], PATHINFO_EXTENSION);
        $sql = "INSERT INTO gambar(extension,idmovie) VALUES (?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('si', $ext, $idmovie);
        $stmt->execute();
        $idposter = $stmt->insert_id;
        move_uploaded_file($posters['tmp_name'][$i], "img/$idposter.$ext");
    }
}
if (isset($_POST['idpemain'])) {
    $idpemains = $_POST['idpemain'];
    $perans = $_POST['peran'];
    foreach ($idpemains as $idx => $idpemain) {
        $stmt = $mysqli->prepare("INSERT INTO detail_pemain VALUES(?,?,?)");
        $stmt->bind_param('iis', $idmovie, $idpemain, $perans[$idx]);
        $stmt->execute();
    }
}

$stmt->close();
$mysqli->close();

header("location: insertmovie.php");
