<?php

namespace db;

interface IGateway
{
    public function find(array $data) : array ;

    public function update(array $data) : bool ;

    public function insert(array $data) : bool ;

    public function delete(array $data) : bool ;

}