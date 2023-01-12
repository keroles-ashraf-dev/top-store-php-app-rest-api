<?php

namespace System;

abstract class Model
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    protected $app;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * records count
     *
     * @var int
     */
    protected $count;

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get all Model Records
     *
     * @return array
     */
    public function all($table = null)
    {
        return $this->orderBy('id')->fetchAll($table ?: $this->table);
    }

    /**
     * Get Record By Id
     *
     * @param int $id
     * @return \stdClass | null
     */
    public function get($id, $table = null)
    {
        return $this->where('id = ?', $id)->fetch($table ?: $this->table);
    }

    /**
     * Determine if the given value of the key exists in table
     *
     * @param mixed $value
     * @param string $key
     * @return bool
     */
    public function exists($value, $key = 'id', $table = null)
    {
        return (bool) $this->select($key)->where($key . '=?', $value)->fetch($table ?: $this->table);
    }

    /**
     * get records count
     *
     * @return int
     */
    public function count($table = null, $filterField = null, $filterValue = null)
    {
        if ($filterValue && $filterField) {
            $this->count = $this->select('COUNT(id) AS `total`')
                ->where($filterField . '=?', $filterValue)
                ->fetch($table ?: $this->table)->total;
        } else {
            $this->count = $this->select('COUNT(id) AS `total`')->fetch($table ?: $this->table)->total;
        }
        return $this->count;
    }

    /**
     * Delete Record By Id
     *
     * @param int $id
     * @return void
     */
    public function delete($id, $table = null)
    {
        return $this->where('id = ?', $id)->delete($table ?: $this->table);
    }

    /**
     * Call Database methods dynamically
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

    /**
     * Call shared application objects dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
}
