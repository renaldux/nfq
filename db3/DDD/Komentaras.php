<?php
namespace DDD;

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
}