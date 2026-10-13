
<?php
// Function untuk menentukan predikat
function predikatNilai($rataRata) {
    if ($rataRata >= 85) {
        return "A";
    } elseif ($rataRata >= 75) {
        return "B";
    } elseif ($rataRata >= 60) {
        return "C";
    } else {
        return "D";
    }
}

// Function untuk mengamankan output
function aman($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, "UTF-8");
}

$data = [];
$nilai = [];
$rataRata = 0;
$predikat = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data["nim"] = trim($_POST["nim"] ?? "");
    $data["nama"] = trim($_POST["nama"] ?? "");
    $data["prodi"] = trim($_POST["prodi"] ?? "");

    $nilai = [
        "Tugas" => (float)($_POST["tugas"] ?? 0),
        "UTS" => (float)($_POST["uts"] ?? 0),
        "UAS" => (float)($_POST["uas"] ?? 0)
    ];

    // Menghitung total menggunakan perulangan
    $total = 0;

    foreach ($nilai as $angka) {
        $total += $angka;
    }

    // Menghitung rata-rata
    $rataRata = $total / count($nilai);

    // Menentukan kelulusan dan predikat
    $predikat = predikatNilai($rataRata);
    $status = ($rataRata >= 70) ? "LULUS" : "TIDAK LULUS";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Nilai Mahasiswa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 14px;
            background: #8f8f8f;
            font-family: Arial, sans-serif;
            color: #111;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 22px;
            background: #fff;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #244f7d;
            font-size: 34px;
            margin: 18px 0 34px;
        }

        label {
            display: block;
            margin: 10px 0 4px;
            font-size: 15px;
        }

        input {
            width: 100%;
            height: 55px;
            padding: 5px 13px;
            border: 1px solid #999;
            background: white;
            font-size: 15px;
        }

        input:focus {
            outline: 1px solid #244f7d;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 13px;
            border: none;
            border-radius: 2px;
            background: #244f7d;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #193b60;
        }

        .hasil {
            margin-top: 22px;
            padding: 20px;
            background: #edf5fb;
            border-radius: 6px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .hasil h3 {
            margin: 0 0 8px;
            color: #244f7d;
            font-size: 22px;
        }

        .hasil p {
            margin: 3px 0;
            font-size: 14px;
        }

        .lulus {
            color: #16803d;
            font-weight: bold;
        }

        .tidak-lulus {
            color: #c62828;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 16px;
            }

            h2 {
                font-size: 25px;
                margin: 10px 0 25px;
            }

            label {
                font-size: 15px;
            }

            input {
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Perhitungan Nilai Mahasiswa</h2>

    <form method="POST">
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim"
            value="<?= aman($data["nim"] ?? "") ?>" required>

        <label for="nama">Nama Mahasiswa</label>
        <input type="text" id="nama" name="nama"
            value="<?= aman($data["nama"] ?? "") ?>" required>

        <label for="prodi">Program Studi</label>
        <input type="text" id="prodi" name="prodi"
            value="<?= aman($data["prodi"] ?? "") ?>" required>

        <label for="tugas">Nilai Tugas (0–100)</label>
        <input type="number" id="tugas" name="tugas"
            min="0" max="100" step="any"
            value="<?= aman($_POST["tugas"] ?? "") ?>" required>

        <label for="uts">Nilai UTS (0–100)</label>
        <input type="number" id="uts" name="uts"
            min="0" max="100" step="any"
            value="<?= aman($_POST["uts"] ?? "") ?>" required>

        <label for="uas">Nilai UAS (0–100)</label>
        <input type="number" id="uas" name="uas"
            min="0" max="100" step="any"
            value="<?= aman($_POST["uas"] ?? "") ?>" required>

        <button type="submit">Simpan Data</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <div class="hasil">
            <h3>Data Mahasiswa</h3>

            <p><b>NIM:</b> <?= aman($data["nim"]) ?></p>
            <p><b>Nama:</b> <?= aman($data["nama"]) ?></p>
            <p><b>Program Studi:</b> <?= aman($data["prodi"]) ?></p>

            <h3 style="margin-top:15px;">Hasil Perhitungan Nilai</h3>

            <?php foreach ($nilai as $jenis => $angka): ?>
                <p><b>Nilai <?= aman($jenis) ?>:</b>
                    <?= aman($angka) ?></p>
            <?php endforeach; ?>

            <p><b>Rata-rata:</b>
                <?= number_format($rataRata, 2, ",", ".") ?></p>

            <p><b>Predikat:</b> <?= aman($predikat) ?></p>

            <p><b>Status Kelulusan:</b>
                <span class="<?= $status === "LULUS"
                    ? "lulus" : "tidak-lulus" ?>">
                    <?= aman($status) ?>
                </span>
            </p>
        </div>
    <?php endif; ?>

</div>
</body>
</html>

