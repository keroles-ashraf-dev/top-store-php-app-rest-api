<?php

namespace App\Models;

use System\Model;

class CartModel extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'cart';

	/**
	 * table count
	 *
	 * @var int
	 */
	protected $count;

	/**
	 * get user cart
	 *
	 * @return array
	 */
	public function getCartProducts($id)
	{
		if (!$this->app->isSharing('userCart')) {

			$products = $this->select('p.id, p.price, d.price discounted_price, p.name ,c.count, p.available_count, pi.name image')
				->from($this->table . ' c')
				->join('LEFT JOIN products p ON c.product_id = p.id')
				->join('LEFT JOIN deals d ON p.id = d.product_id')
				->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
				->where('c.user_id = ? AND p.status = ?', $id, 1)
				->fetchAll();

			$this->app->share('userCart', $products);
		}

		return $this->app->get('userCart');
	}

	/**
	 * add to cart
	 *
	 * @param int $userId
	 * @param int $productId
	 * @return bool
	 */
	public function add($userId, $productId)
	{
		$product = $this->select('count')
			->from($this->table)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->fetch();

		if (empty($product) || empty($product->count)) {

			$this
				->data('user_id', $userId)
				->data('product_id', $productId)
				->data('count', 1)
				->insert($this->table);
			return true;
		}

		$this
			->data('count', $product->count + 1)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->update($this->table);
		return true;
	}

	/**
	 * increment product in cart
	 *
	 * @param int $userId
	 * @param int $productId
	 * @return bool
	 */
	public function increment($userId, $productId)
	{
		$product = $this->select('count')
			->from($this->table)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->fetch();

		$this
			->data('count', $product->count + 1)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->update($this->table);

		return true;
	}

	/**
	 * decrement product in cart
	 *
	 * @param int $userId
	 * @param int $productId
	 * @return bool
	 */
	public function decrement($userId, $productId)
	{
		$product = $this->select('*')
			->from($this->table)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->fetch();

		if (($product->count - 1) < 1) {

			$this->delete($product->id);
			return true;
		}

		$this
			->data('count', $product->count - 1)
			->where('user_id = ? AND product_id = ?', $userId, $productId)
			->update($this->table);

		return true;
	}

	/**
	 * clear user cart
	 *
	 * @return array
	 */
	public function clearUserCart($userId)
	{
		$this->where('user_id = ?', $userId)->delete($this->table);
	}

	/**
	 * count of cart products
	 *
	 * @param int $userId
	 * @return int
	 */
	public function cartCount($userId)
	{
		$productsCount = $this->select('count')
			->from($this->table)
			->where('user_id = ?', $userId)
			->fetchAll();

		$count = 0;

		foreach ($productsCount as $productCount) {

			$count = $count + $productCount->count;
		}

		return $count;
	}
}
