<?php
// Mendefinisikan dua buah variabel angka
$angka1 = 20;
$angka2 = 5;

echo "<h3>Contoh Operasi Aritmatika dalam PHP</h3>";
echo "Nilai Angka 1 = $angka1 <br>";
echo "Nilai Angka 2 = $angka2 <br><hr>";

// 1. Penjumlahan (+)
$penjumlahan = $angka1 + $angka2;
echo "Hasil Penjumlahan ($angka1 + $angka2) = <strong>$penjumlahan</strong><br>";

// 2. Pengurangan (-)
$pengurangan = $angka1 - $angka2;
echo "Hasil Pengurangan ($angka1 - $angka2) = <strong>$pengurangan</strong><br>";

// 3. Perkalian (*)
$perkalian = $angka1 * $angka2;
echo "Hasil Perkalian ($angka1 * $angka2) = <strong>$perkalian</strong><br>";

// 4. Pembagian (/)
$pembagian = $angka1 / $angka2;
echo "Hasil Pembagian ($angka1 / $angka2) = <strong>$pembagian</strong><br>";

// 5. Modulus / Sisa Bagi (%)
$modulus = $angka1 % $angka2;
echo "Sisa Bagi / Modulus ($angka1 % $angka2) = <strong>$modulus</strong><br>";

// 6. Perpangkatan (**) - PHP 5.6 ke atas
$pangkat = $angka1 ** 2;
echo "Perpangkatan ($angka1 pangkat 2) = <strong>$pangkat</strong><br>";
?>