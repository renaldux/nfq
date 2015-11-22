<?php
namespace ActiveRecord;

require_once "../db.php";
require_once "Tema.php";

use db\db;

class Komentaras
{
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
    private $komentaras;
    /**
     * @var
     */
    private $autorius;
    /**
     * @var
     */
    private $temos_id;

    /**
     * @return mixed
     */

    private $db;

    /**
     * Komentaras constructor.
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
    public function getKomentaras()
    {
        return $this->komentaras;
    }

    /**
     * @param mixed $komentaras
     */
    public function setKomentaras($komentaras)
    {
        $this->komentaras = $komentaras;
    }

    /**
     * @return mixed
     */
    public function getAutorius()
    {
        return $this->autorius;
    }

    /**
     * @param mixed $autorius
     */
    public function setAutorius($autorius)
    {
        $this->autorius = $autorius;
    }

    /**
     * @return mixed
     */
    public function getTemosId()
    {
        return $this->temos_id;
    }

    /**
     * @param mixed $temos_id
     */
    public function setTemosId($temos_id)
    {
        $this->temos_id = $temos_id;
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
            $names = ['John', 'Mary', 'Anthony', 'Phil', 'Gabriel'];
            for ($i = 0; $i < $count; $i++) {
                $data[$i]['data'] = date("Y-m-d H:i:s", time() - rand(100, 5000));
                $data[$i]['temos_id'] = round(rand(1, 20));
                $data[$i]['komentaras'] = 'Komentaras' . $i;
                $data[$i]['autorius'] = $names[array_rand($names)];
            }

            $query = $this->db->prepare("INSERT INTO komentarai(`temos_id`, `komentaras`, `autorius`, `data`) VALUES(:temos_id, :komentaras, :autorius, :data)");
            foreach($data as $entry) {
                $query->bindParam(':temos_id', $entry["temos_id"], \PDO::PARAM_INT);
                $query->bindParam(':komentaras', $entry["komentaras"], \PDO::PARAM_STR);
                $query->bindParam(':autorius', $entry["autorius"], \PDO::PARAM_STR);
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

    public function save()
    {
        $response = [];
        try {
            $query = $this->db->prepare("INSERT INTO komentarai(`temos_id`, `komentaras`, `autorius`, `data`) VALUES(:temos_id, :komentaras, :autorius, :data)");
            $query->bindParam(':temos_id', $this->getTemosId(), \PDO::PARAM_INT);
            $query->bindParam(':komentaras', $this->getKomentaras(), \PDO::PARAM_STR);
            $query->bindParam(':autorius', $this->getAutorius(), \PDO::PARAM_STR);
            $query->bindParam(':data', $this->getData(), \PDO::PARAM_STR);
            $query->execute();
            $response['status'] = 'success';
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $response = [];
        try{
            $query = $this->db->prepare("SELECT * FROM `komentarai`");
            $query->execute( );
            $query->setFetchMode(\PDO::FETCH_OBJ);
            $resAr = [];
            foreach (new \RecursiveArrayIterator($query->fetchAll()) as $r){
                $komentaras = new self();
                $komentaras->setData($r->data);
                $komentaras->setAutorius($r->autorius);
                $komentaras->setKomentaras($r->komentaras);
                $komentaras->setId($r->id);
                $komentaras->setTemosId($r->temos_id);
                $resAr[] = $komentaras;
            }
            $response['status'] = 'success';
            $response['data'] = $resAr;
        }catch (\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}