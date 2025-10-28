<?php

namespace db;

class URLGateWay implements IGateway
{
    private Connection $con;

    public function __construct()
    {
        global $env;

        $this->con = new Connection(
            "mysql:host=".$env['MYSQL_HOST'].";dbname=".$env['MYSQL_DATABASE'],
            $env['MYSQL_USER'],
            $env['MYSQL_PASSWORD']
        );
    }

    public function find(array $data) : array // [
    {
        $query = 'Select longUrl From Links Where shortUrl = :url';
        $this->con->executeQuery($query, [':url' => [$data["short"], 2]]);
        return $this->con->getResults();
    }

    public function update(array $data) : bool // TODO
    {return false;}

    public function insert(array $data) : bool
    {
        $this->con->beginTransaction();
        try {
            $query = 'Insert Into Links(longUrl, shortUrl) Values (:long, :short)';
            $this->con->executeQuery($query, [
                ":long" => [$data["long"], 2],
                ":short" => [$data["short"], 2]
            ]);
            $this->con->commit();
            return true;
        } catch (\PDOException) {
            $this->con->rollBack();
            return false;
        }

    }

    public function delete(array $data) : bool // TODO
    {return false;}
}