<?php

namespace App\Models;

use System\Model;

class AddressesModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * users Addresses Linking Table name
     *
     * @var string
     */
    private $usersAddressesTable = 'users_addresses';

    /**
     * get User Addresses
     *
     * @return int
     */
    public function getAddresses($userId)
    {
        if (!$this->app->isSharing('addresses')) {

            $addresses = $this->select('a.*')
                ->from($this->usersAddressesTable . ' ua')
                ->join('LEFT JOIN addresses a ON ua.address_id = a.id')
                ->where('ua.user_id = ?', $userId)
                ->fetchAll();

            $this->app->share('addresses', $addresses);
        }

        return $this->app->get('addresses');
    }

    /**
     * get Address
     *
     * @return int
     */
    public function getAddress($addressId)
    {
        return $this->select('*')
            ->from($this->table)
            ->where('id = ?', $addressId)
            ->fetch();
    }


    /**
     * Create New Record
     *
     * @return int
     */
    public function create($userId)
    {
        $addressId = $this
            ->data('country', $this->request->post('country'))
            ->data('state', $this->request->post('state'))
            ->data('city', $this->request->post('city'))
            ->data('area', $this->request->post('area'))
            ->data('street', $this->request->post('street'))
            ->data('building', $this->request->post('building'))
            ->data('floor', $this->request->post('floor'))
            ->data('postcode', $this->request->post('postcode'))
            ->data('nearest_landmark', $this->request->post('nearest-landmark'))
            ->insert($this->table)->lastId();

        $this
            ->data('user_id', $userId)
            ->data('address_id', $addressId)
            ->insert($this->usersAddressesTable);

        return $addressId;
    }

    /**
     * Update Record By Id
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $this->data('country', $this->request->post('country'))
            ->data('state', $this->request->post('state'))
            ->data('city', $this->request->post('city'))
            ->data('area', $this->request->post('area'))
            ->data('street', $this->request->post('street'))
            ->data('building', $this->request->post('building'))
            ->data('floor', $this->request->post('floor'))
            ->data('postcode', $this->request->post('postcode'))
            ->data('nearest_landmark', $this->request->post('nearest-landmark'))
            ->where('id = ?', $id)
            ->update($this->table);

        return true;
    }

    /**
     * Delete Record By Id
     *
     * @param int $id
     * @return void
     */
    public function delete($id, $table = null)
    {
        $this->where('address_id = ?', $id)->delete($table ?: $this->usersAddressesTable);

        return true;
    }
}
