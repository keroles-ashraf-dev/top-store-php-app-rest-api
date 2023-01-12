<?php

namespace App\Models;

use System\Model;

class UsersModel extends Model
{
  /**
   * Table name
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * auth tokens table name
   *
   * @var string
   */
  private $authTokensTable = 'auth_tokens';

  /**
   * users count
   *
   * @var int
   */
  protected $count;

  /**
     * get user data by token
     * 
     * @param string $token
     * @return \stdClass
     */
    public function getUserByToken($token)
    {
        $userId = $this->select('user_id')->where('token=?', $token)->fetch($this->authTokensTable)->user_id;
        $user = $this->select('*')->where('id=?', $userId)->fetch($this->table);

        return $user;
    }

  /**
   * Create New User
   *
   * @return String $id
   */
  public function create($user)
  {

    $id = $this
      ->data('first_name', $user['first-name'])
      ->data('last_name', $user['last-name'])
      ->data('email', $user['email'])
      ->data('email_verified', 0)
      ->data('phone', $user['phone'])
      ->data('phone_verified', 0)
      ->data('password', password_hash($user['password'], PASSWORD_DEFAULT))
      ->data('created', time())
      ->data('status', true)
      ->data('ip', $this->request->server('REMOTE_ADDR'))
      ->insert($this->table)->lastId();

    $this
      ->data('user_id', $id)
      ->data('token', '')
      ->insert($this->authTokensTable);

      return $id;
  }

  /**
   * paginate users
   *
   * @return array
   */
  public function paginate()
  {
    // We Will get the current page
    $currentPage = $this->pagination->page();
    // We Will get the items Per Page
    $limit = $this->pagination->itemsPerPage();
    // Set our offset
    $offset = $limit * ($currentPage - 1);
    //set total items
    $this->pagination->setTotalItems($this->count());

    return $this->select('*')
      ->from($this->table)
      ->orderBy('id', 'DESC')
      ->limit($limit, $offset)
      ->fetchAll();
  }

  /**
   * search in Users
   *
   * @var string $value
   * @return array
   */
  public function search($value)
  {
    return $this->select('*')->from($this->table)
      ->where('phone = ? OR email = ? OR id = ?', $value, $value, $value)
      ->fetchAll();
  }

  /**
   * Update Users Record By Id
   *
   * @param array $user
   * @return bool
   */
  public function update($userData)
  {
    if (isset($userData['password'])) {
      $this->data('password', password_hash($userData['password'], PASSWORD_DEFAULT));
    }
    if (isset($userData['first-name'])) {
      $this->data('first_name', $userData['first-name']);
    }
    if (isset($userData['last-name'])) {
      $this->data('last_name', $userData['last-name']);
    }
    if (isset($userData['email'])) {
      $this->data('email', $userData['email']);
    }
    if (isset($userData['email_verified'])) {
      $this->data('email_verified', $userData['email_verified']);
    }
    if (isset($userData['phone'])) {
      $this->data('phone', $userData['phone']);
    }
    if (isset($userData['role'])) {
      $this->data('role', $userData['role']);
    }
    if (isset($userData['status'])) {
      $this->data('status', $userData['status']);
    }
    if (isset($userData['default-address-id'])) {
      $this->data('default_address_id', $userData['default-address-id']);
    }

    $this
      ->where('id = ?', $userData['id'])
      ->update($this->table);

    return true;
  }

  /**
   * update users status
   *
   * @param int $id
   * @param int $status
   * @return void
   */
  public function updateStatus($id, $status)
  {
    $this
      ->data('status', $status)
      ->where('id=?', $id)
      ->update($this->table);
  }
}
