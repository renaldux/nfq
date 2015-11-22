<?php

namespace DDD;

require_once "KomentaruRepo.php";
require_once "TemuRepo.php";

$action = isset ($_GET['action'])?$_GET['action']:null;

if( empty($action)) {
    echo "<a href='?action=gentem'><h1>Generuoti temas</h1></a>";
    echo "<a href='?action=genkom'><h1>Generuoti komentarus</h1></a>";
}
if($action == 'gentem'){
    $temuRepo = new TemuRepo();
    $generuok = $temuRepo->generateEntries();
    if($generuok['status'] == 'success') {
        echo "<h1>Jūs sėkmingai sugeneravote temas</h1>";
    }else{
        echo "<h1>Klaida</h1>";
        echo "<p>".$generuok['message']."</p>";
    }

}elseif($action == 'genkom'){
    $komentaruRepo = new KomentaruRepo();
    $generuok = $komentaruRepo->generateEntries();
    if($generuok['status'] == 'success') {
        echo "<h1>Jūs sėkmingai sugeneravote komentarus</h1>";
    }else{
        echo "<h1>Klaida</h1>";
        echo "<p>".$generuok['message']."</p>";
    }
}
if( !empty($action)) {
    echo "<a href='generuoti.php'>Grįžti į generavimo puslapį</a> | ";
    echo "<a href='listController.php'>Peržiūrėti sugeneruotus duomenis</a>";
}
