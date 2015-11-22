<?php

namespace DDD;

require_once "Komentaras.php";
require_once "Tema.php";
require_once "../db.php";

use db\db;

class TemuRepo
{

    /**
     * @var $db
     */
    private $db;

    /**
     * TemuRepo constructor.
     */
    public function __construct()
    {
        $this->db = db::getInstance();
    }


    public function getAll($withComments = false)
    {
        $response = [];
        try{
            $query = $this->db->prepare("
            SELECT temos.*, COUNT(komentarai.id) AS komentaru_sk
            FROM temos LEFT JOIN komentarai ON komentarai.temos_id = temos.id
            GROUP BY temos.id");

            $query->execute( );
            $query->setFetchMode(\PDO::FETCH_OBJ);
            $resAr = [];
            foreach (new \RecursiveArrayIterator($query->fetchAll()) as $r) {
                //var_dump($r); exit;
                $tema = new Tema();
                $tema->setData($r->data);
                $tema->setId($r->id);
                $tema->setKomentaruSkaicius($r->komentaru_sk);
                $tema->setPavadinimas($r->pavadinimas);
                if ($withComments) {
                    $comments = $this::getComments($tema);
                    if ($comments['status'] = 'success') {
                        $tema->setKomentarai($comments['data']);
                    }
                }
                $resAr[] = $tema;
            }
            $response['status'] = 'success';
            $response['data'] = $resAr;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getComments( Tema $tema )
    {
        $response = [];
        try{
            if( !is_numeric( $tema->getId() ) ){
                throw new \Exception ('object is empty');
            }
            $query = $this->db->prepare("
            SELECT *
            FROM komentarai
            WHERE temos_id = :temosid");
            $query->bindParam(':temosid', $tema->getId(), \PDO::PARAM_INT);
            $query->execute( );
            $query->setFetchMode(\PDO::FETCH_OBJ);
            $comments = [];
            foreach (new \RecursiveArrayIterator($query->fetchAll()) as $r){
                $komentaras = new Komentaras();
                $komentaras->setId($r->id);
                $komentaras->setData($r->data);
                $komentaras->setKomentaras($r->komentaras);
                $komentaras->setAutorius($r->autorius);
                $komentaras->setTemosId($r->temos_id);
                $comments[] = $komentaras;
            }

            $response['status'] = 'success';
            $response['data'] = $comments;
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @param Tema $tema
     * @return array
     */
    public function save(Tema $tema)
    {
        $response = [];
        try {
            $query = $this->db->prepare("INSERT INTO temos(`pavadinimas`, `data`) VALUES(:title, :time)");
            $query->bindParam(':title', $tema->getPavadinimas(), \PDO::PARAM_STR);
            $query->bindParam(':time', $tema->getData(), \PDO::PARAM_STR);
            $query->execute();
            $response['status'] = 'success';
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function find($id, $withComments=false)
    {
        $response = [];
        try{
            if( !is_numeric($id) ){
                throw new \Exception ("wrong id");
            }
            $query = $this->db->prepare("
                    SELECT temos.*, COUNT(komentarai.id) AS komentaru_skaicius FROM temos
                    LEFT JOIN komentarai ON komentarai.temos_id = temos.id
                    WHERE temos.id = :id
                    GROUP BY temos.id
                    LIMIT 1
                    ");
            $query->bindParam(':id', $id, \PDO::PARAM_INT);
            $query->execute( );
            $query->setFetchMode(\PDO::FETCH_OBJ);
            $result = $query->fetchObject();
            if( !empty($result) ){
                $tema = new Tema();
                $tema->setId($result->id);
                $tema->setData($result->data);
                $tema->setPavadinimas($result->pavadinimas);
                $tema->setKomentaruSkaicius($result->komentaru_skaicius);
                if ($withComments) {
                    $comments = $this::getComments($tema);
                    if ($comments['status'] = 'success') {
                        $tema->setKomentarai($comments['data']);
                    }
                }
            }

            $response['status'] = 'success';
            $response['data'] = $tema;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @param int $count
     * @return array
     */
    public function generateEntries($count = 20){
        $response = [];
        try{
            if( !is_numeric($count) or $count < 1 ){
                throw new \Exception ("wrong number given");
            }
            $data = [];
            $topics = ['Sportas', 'Laisvalaikis', 'Kriminalai', 'Pramogos', 'Nuomones'];
            for ($i = 0; $i < $count; $i++) {
                $data[$i]['data'] = date("Y-m-d H:i:s", time() - rand(5000, 100000));
                $data[$i]['pavadinimas'] = $topics[array_rand($topics)];
            }

            $query = $this->db->prepare("INSERT INTO temos(`pavadinimas`, `data`) VALUES(:pavadinimas, :data)");
            foreach($data as $entry) {
                $query->bindParam(':pavadinimas', $entry["pavadinimas"], \PDO::PARAM_STR);
                $query->bindParam(':data', $entry["data"], \PDO::PARAM_STR);
                $query->execute();
            }
            $response['status'] = 'success';
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}