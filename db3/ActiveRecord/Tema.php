<?php
namespace ActiveRecord;

require_once "../db.php";
require_once "Komentaras.php";

use db\db;


class Tema {
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $pavadinimas;
    /**
     * @var
     */
    private $komentaru_skaicius;
    /**
     * @return mixed
     */

    private $komentarai = [];

    /**
     * @var
     */
    private $db;

    /**
     * Tema constructor.
     */
    public function __construct()
    {
        $this->db = db::getInstance();
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getPavadinimas()
    {
        return $this->pavadinimas;
    }

    /**
     * @param mixed $pavadinimas
     */
    public function setPavadinimas($pavadinimas)
    {
        $this->pavadinimas = $pavadinimas;
    }

    /**
     * @return mixed
     */

    /**
     * @return mixed
     */
    public function getKomentaruSkaicius()
    {
        return $this->komentaru_skaicius;
    }

    /**
     * @param mixed $komentaru_skaicius
     */
    public function setKomentaruSkaicius($komentaru_skaicius)
    {
        $this->komentaru_skaicius = $komentaru_skaicius;
    }

    /**
     * @return mixed
     */
    public function getKomentarai()
    {
        return $this->komentarai;
    }

    /**
     * @param mixed $komentarai
     */
    public function setKomentarai($komentarai)
    {
        $this->komentarai = $komentarai;
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
            $query->bindParam(':title', $this->getPavadinimas(), \PDO::PARAM_STR);
            $query->bindParam(':time', $this->getData(), \PDO::PARAM_STR);
            $query->execute();
            $response['status'] = 'success';
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
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
                $tema = new self();
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
    public function getComments( self $tema )
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