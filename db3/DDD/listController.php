<?php
namespace DDD;

require_once "TemuRepo.php";
echo "<a href='generuoti.php'>Generuoti duomenis</a>";
$temuRepo = new TemuRepo();

$res = $temuRepo->getAll(true);
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
