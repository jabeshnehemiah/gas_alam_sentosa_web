<?php
$mysqli = new mysqli('localhost', 'root', '', 'database');
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
$res = $mysqli->query("SELECT * FROM genre");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="insertmovie_proses.php" method="post" enctype="multipart/form-data">
        <label for="txtJudul">Judul</label>
        <input type="text" name="txtJudul" id="txtJudul" required><br>
        <label for="dtpRilis">Tgl.Rilis</label>
        <input type="date" name="dtpRilis" id="dtpRilis" required><br>
        <label for="numSkor">Skor</label>
        <input type="number" step="0.1" name="numSkor" id="numSkor" required><br>
        <label for="txtSinopsis">Sinopsis</label>
        <input type="text" name="txtSinopsis" id="txtSinopsis" required><br>
        <label>Serial</label>
        <input type="radio" name="rdoSerial" id="rdoYa" value="1" required>
        <label for="rdoYa">Ya</label>
        <input type="radio" name="rdoSerial" id="rdoTidak" value="0">
        <label for="rdoTidak">Tidak</label><br>
        <label>Genre</label>
        <?php
        while ($row = $res->fetch_assoc()) {
            echo "<input type='checkbox' name='genre[]' value='" . $row['idgenre'] . "' id='" . $row['nama'] . "'>";
            echo "<label for='" . $row['nama'] . "'>" . $row['nama'] . "</label>";
        }
        echo "<br>";
        ?>
        <label>Poster</label><br>
        <div id="file_upload">
            <div><input type="file" name="poster[]"><input type="button" class="btnHapus" value="Hapus"></div>
        </div>
        <input type="button" id="btnTambah" value="Tambah"><br>
        <label>Pemain</label>
        <select id="selPemain">
            <option value="">-- Pilih Pemain --</option>
            <?php
            $res = $mysqli->query("SELECT * FROM pemain");
            while ($row = $res->fetch_assoc()) {
                echo "<option value='" . $row['idpemain'] . "'>" . $row['nama'] . "</option>";
            }
            ?>
        </select>
        <select id="selPeran">
            <option value="">-- Pilih Peran --</option>
            <option value="Utama">Utama</option>
            <option value="Pembantu">Pembantu</option>
            <option value="Cameo">Cameo</option>
        </select><br>
        <table border="1">
            <thead>
                <tr>
                    <th>Pemain</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="kumpulanPemain"></tbody>
        </table>
        <input type="button" id="btnTambahPemain" value="Tambah">
        <input type="submit" value="Submit">
    </form>
</body>
<script src="jquery-3.5.1.min.js"></script>
<script>
    const perans = ['Utama', 'Pembantu', 'Cameo'];
    $('#btnTambah').click(function() {
        $('#file_upload').append('<div><input type="file" name="poster[]"><input type="button" class="btnHapus" value="Hapus"></div>');
    })
    // $('.btnHapus').click(function() {
    $('body').on('click', '.btnHapus', function() {
        $(this).parent().remove();
    })
    $('body').on('click', '#btnTambahPemain', function() {
        let idpemain = $('#selPemain').val();
        let namapemain = $('#selPemain option:selected').text();
        let peran = $('#selPeran').val();
        let barisbaru = "<tr><td>" + namapemain + "<input type='hidden' name='idpemain[]' value='" + idpemain + "'></td><td>";
        barisbaru += "<select name='peran[]'>";
        for (x in perans) {
            barisbaru += "<option value='" + perans[x] + "'";
            barisbaru += perans[x] == peran ? "selected" : "";
            barisbaru += ">" + perans[x] + "</option>";
        }
        barisbaru += "</select>";
        barisbaru += "</td><td><input type='button' class='btnHapusPemain' value='Hapus'></td></tr>";
        $('#kumpulanPemain').append(barisbaru);
    })
    $('body').on('click', '.btnHapusPemain', function() {
        $(this).parent().parent().remove();
    })
</script>

</html>