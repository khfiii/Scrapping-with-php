<?php

use duzun\hQuery;

require_once './vendor/autoload.php'; 

// Tentukan direktori cache
$cacheDir = './cache';

// Buat direktori cache jika belum ada
if (!file_exists($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

hQuery::$cache_path = $cacheDir;

try {
    $doc = hQuery::fromURL('https://scholar.google.co.id/citations?user=6eDyhRgAAAAJ&hl=id');

$dataTahun = $doc->find('.gsc_g_t'); 
$dataJumlahPertahun = $doc->find('.gsc_g_a'); 
$kutipan = $doc->find('.gsc_rsb_std')[0]; 
$h1 = $doc->find('.gsc_rsb_std')[2]; 
$h10 = $doc->find('.gsc_rsb_std')[4]; 

$tahun = []; 
$jumlah = []; 

foreach ($dataTahun as $value) {
    $tahun[] = $value->text();  // Mengambil teks dari elemen
}

foreach ($dataJumlahPertahun as $value) {
    $jumlah[] = $value->text();  // Mengambil teks dari elemen
}

// Menampilkan hasil
$dataGabungan = array_combine($tahun, $jumlah); 

} catch (\Exception $e) {
    $e->getMessage(); 
}

?>


<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </head>

  <body>
   <div style="margin-top:50px; margin-left: 40px;">

   <p>Semua Kutipan : <?= $kutipan ?> </p>
   <p>indeks-h : <?= $h1 ?> </p>
   <p>indeks i10 : <?= $h10 ?> </p>

    </div>
    <div id="curve_chart" style="margin-top: 100px;"></div>
  </body>

  <script type="text/javascript">
     google.charts.load('current', {packages: ['corechart', 'line']});
     google.charts.setOnLoadCallback(drawBasic);
     let getDataFromScrapping = <?= json_encode($dataGabungan); ?>;
    
     let formatedData = Object.entries(getDataFromScrapping).map(([tahun, value]) => [tahun, parseInt(value)]);

     formatedData.unshift(['Tahun', 'Jumlah Kutipan']); 
   
     function drawBasic()
     {

        let data = google.visualization.arrayToDataTable(formatedData); 
        
        var options = {
        hAxis: {
          title: 'Tahun'
        },
        vAxis: {
          title: 'Jumlah Kutipan'
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

      chart.draw(data, options);
     }


     
</script>
</html>