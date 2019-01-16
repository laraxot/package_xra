<?php



namespace XRA\XRA\Interfaces;

//https://medium.com/employbl/use-the-repository-design-pattern-in-a-laravel-application-13f0b46a3dce
//http://lyften.com/projects/laravel-repository/doc/searching.html !!!!
// ogni pacchetto dovrebbe essere cosi' https://github.com/Torann/laravel-repository
// https://github.com/Torann/laravel-repository/blob/master/src/Repositories/AbstractRepository.php
interface BaseRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id);
}
