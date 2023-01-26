<?php

namespace App\Models;

use System\Model;

class OrdersModel extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'orders';

	/**
	 * order items table name
	 *
	 * @var string
	 */
	private $orderItemsTable = 'order_items';

	/**
	 * table count
	 *
	 * @var int
	 */
	protected $count;

	/**
	 * get user orders
	 *
	 * @return array
	 */
	public function getOrders($userId)
	{
		if (!$this->app->isSharing('orders')) {

			$orders = $this->select('o.id, o.created, o.total, o.status, o.payment_type, o.payment_status, oi.count, oi.price, p.id product_id, p.name, pi.name image')
				->from($this->table . ' o')
				->join('LEFT JOIN order_items oi ON o.id = oi.order_id')
				->join('LEFT JOIN products p ON oi.product_id = p.id')
				->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
				->where('o.user_id = ?', $userId)
				->orderBy('created', 'DESC')
				->fetchAll();

			$formattedOrders = [];

			for ($i = 0; $i < count($orders); $i++) {

				for ($j = 0; $j < count($formattedOrders); $j++) {

					if (isset($formattedOrders[$j]) && $formattedOrders[$j]['id'] == $orders[$i]->id) {

						$formattedOrders[$j]['items'][] = [
							'id' => $orders[$i]->product_id,
							'name' => $orders[$i]->name,
							'image' => $orders[$i]->image,
							'price' => $orders[$i]->price,
							'count' => $orders[$i]->count,
						];

						continue 2;
					}
				}

				$formattedOrders[$i] = [
					'id' => $orders[$i]->id,
					'created' => $orders[$i]->created,
					'total' => $orders[$i]->total,
					'status' => $orders[$i]->status,
					'payment_type' => $orders[$i]->payment_type,
					'payment_status' => $orders[$i]->payment_status,
					'items' => [],
				];

				$formattedOrders[$i]['items'][] = [
					'id' => $orders[$i]->product_id,
					'name' => $orders[$i]->name,
					'image' => $orders[$i]->image,
					'price' => $orders[$i]->price,
					'count' => $orders[$i]->count,
				];
			}

			$this->app->share('orders', $formattedOrders);
		}

		return $this->app->get('orders');
	}

	/**
	 * Create New order
	 *
	 * @return int
	 */
	public function create($orderData)
	{
		$orderId = $this
			->data('user_id', $orderData['user-id'])
			->data('total', $orderData['total'])
			->data('subtotal', $orderData['subtotal'])
			->data('shipping', $orderData['shipping'])
			->data('vat', $orderData['vat'])
			->data('status', $orderData['status'])
			->data('payment_type', $orderData['payment-type'])
			->data('address_id', $orderData['address-id'])
			->data('payment_status', $orderData['payment-status'])
			->data('created', $orderData['created'])
			->insert($this->table)->lastId();

		$items = $orderData['items'];

		foreach ($items as $item) {

			if (empty($item->discounted_price)) {
				$this->data('price', $item->price);
			} else {
				$this->data('price', $item->discounted_price);
			}

			$this
				->data('order_id', $orderId)
				->data('product_id', $item->id)
				->data('count', $item->count)
				->insert($this->orderItemsTable);
		}

		return $orderId;
	}

		/**
     * update record status
     *
     * @param int $id
     * @param int $status
     * @return void
     */
    public function updatePaymentData($id, $status, $paymentId, $paymentStatus)
    {
        $this
            ->data('status', $status)
            ->data('payment_id', $paymentId)
            ->data('payment_status', $paymentStatus)
            ->where('id=?', $id)
            ->update($this->table);
    }

	/**
     * update record status
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
