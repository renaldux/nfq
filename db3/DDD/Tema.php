<?php
namespace DDD;

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
}