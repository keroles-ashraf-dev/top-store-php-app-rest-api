<?php

namespace App\Models;

use System\Model;

class ApiKeysModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'api_keys';

    /**
     * Determine if the given api key valid
     *
     * @param string $key
     * @return bool
     */
    public function isValidKey($key)
    {
        $apiKey = $this->where('api_key = ?', $key)->fetch($this->table);

        if (!$apiKey) {
            return false;
        }

        return true;
    }
}
