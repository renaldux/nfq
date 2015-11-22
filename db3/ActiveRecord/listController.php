<?php
namespace ActiveRecord;

require_once "Tema.php";

echo "<a href='generuoti.php'>Generuoti duomenis</a>";

$tema = new Tema();
$res = $tema->getAll(true);
if($res['status'] == 'failed'){
    echo "<h1>Sorry, something went wrong</h1>";
    echo "<p>Message: ".$res['message']."</p>";
    return;
}
$temos = $res['data'];

//isvesk temas
echo "<table width='100%' border='1'>
    <tr>
    <th align='left'>Data</th>
    <th align='left'>Pavadinimas</th>
    <th align='left'>komentaru skaicius</th>
    <th align='left'>komentarai</th>
    </tr>
";
if(empty($temos)){
    echo "<tr><td colspan='4'>Įrašų nėra</td></tr>";
}
foreach ($temos as $tema){
    echo "<tr>
        <td>".$tema->getData()."</td>
        <td>".$tema->getPavadinimas()."</td>
        <td>".$tema->getKomentaruSkaicius()."</td>
        <td>";
    foreach ($tema->getKomentarai() as $komentaras){
        echo $komentaras->getData().' '.$komentaras->getKomentaras().' '.$komentaras->getAutorius()."<br />";
    }
    echo "</td>
        </tr>
    ";
}
echo "</table>";

