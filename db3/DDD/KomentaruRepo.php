<?php
namespace DDD;

require_once "Komentaras.php";
require_once "Tema.php";
require_once "../db.php";


use db\db;


/**
 * Class KomentaruRepo
 * @package DDD
 */
class KomentaruRepo
{

    private $db;
    /**
     * commentsRepo constructor.
     */
    public function __construct()
    {
        $this->db = db::getInstance();
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
                $komentaras = new Komentaras();
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

    /**
     * @param Komentaras $komentaras
     * @return array
     */
    public function save(Komentaras $komentaras)
    {
        $response = [];
        try {
            $query = $this->db->prepare("INSERT INTO komentarai(`temos_id`, `komentaras`, `autorius`, `data`) VALUES(:temos_id, :komentaras, :autorius, :data)");
            $query->bindParam(':temos_id', $komentaras->getTemosId(), \PDO::PARAM_INT);
            $query->bindParam(':komentaras', $komentaras->getKomentaras(), \PDO::PARAM_STR);
            $query->bindParam(':autorius', $komentaras->getAutorius(), \PDO::PARAM_STR);
            $query->bindParam(':data', $komentaras->getData(), \PDO::PARAM_STR);
            $query->execute();
            $response['status'] = 'success';
        }catch(\Exception $e){
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function find($id)
    {
        $response = [];
        try{
            if( !is_numeric($id) ){
                throw new \Exception ("wrong id");
            }
            $query = $this->db->prepare("SELECT * FROM komentarai WHERE id = :id LIMIT 1 ");
            $query->bindParam(':id', $id, \PDO::PARAM_INT);
            $query->execute( );
            $query->setFetchMode(\PDO::FETCH_OBJ);
            $result = $query->fetchObject();
            $komentaras = new Komentaras();
            if( !empty($result) ){
                $komentaras->setId($result->id);
                $komentaras->setData($result->data);
                $komentaras->setKomentaras($result->komentaras);
                $komentaras->setAutorius($result->autorius);
                $komentaras->setTemosId($result->temos_id);
            }

            $response['status'] = 'success';
            $response['data'] = $komentaras;
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

}