<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<style>

body{

    font-family: DejaVu Sans;
    font-size:11px;

}

h2{

    text-align:center;
    margin-bottom:25px;

}

.area-title{

    background:#ddd;
    padding:8px;
    margin-top:25px;
    font-size:14px;
    font-weight:bold;

}

table{

    width:100%;
    border-collapse:collapse;
    margin-top:8px;
    margin-bottom:20px;

}

th{

    background:#efefef;

}

th,
td{

    border:1px solid #000;
    padding:6px;
    text-align:center;

}

</style>

</head>

<body>

<h2>LAPORAN STOK MATERIAL</h2>

@foreach($areas as $area)

<div class="area-title">

Area :
{{ $area->nama_area }}

&nbsp;&nbsp;&nbsp;&nbsp;

Total Stock :
{{ $area->total_stock }}

</div>

<table>

<thead>

<tr>

<th>No</th>
<th>Kode</th>
<th>Nama Material</th>
<th>Satuan</th>
<th>IN</th>
<th>OUT</th>
<th>Stock</th>
<th>Status</th>

</tr>

</thead>

<tbody>

@forelse($area->materials as $material)

@php

$masuk = $material->total_masuk ?? 0;
$keluar = $material->total_keluar ?? 0;
$stock = $masuk-$keluar;

@endphp

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $material->kode_material }}</td>

<td>{{ $material->nama_material }}</td>

<td>{{ $material->satuan }}</td>

<td>{{ $masuk }}</td>

<td>{{ $keluar }}</td>

<td>{{ $stock }}</td>

<td>

@if($stock<=0)

Habis

@elseif($stock<=10)

Menipis

@else

Aman

@endif

</td>

</tr>

@empty

<tr>

<td colspan="8">

Belum ada material

</td>

</tr>

@endforelse

</tbody>

</table>

@endforeach

</body>
</html>