<?php

namespace App\Models;

use System\Model;

class ProductsModel extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * Table name
	 *
	 * @var string
	 */
	private $cartTable = 'cart';

	/**
	 * users count
	 *
	 * @var int
	 */
	protected $count;

	/**
	 * Images Table name
	 *
	 * @var string
	 */
	private $imagesTable = 'products_images';

	/**
	 * Create New Product Record
	 *
	 * @return void
	 */
	public function create()
	{
		$images = [];
		$countFiles = count($_FILES);

		for ($i = 0; $i < $countFiles; $i++) {
			$images[] = $this->uploadImage("product-image-$i");
		}

		$id = $this
			->data('category_id', $this->request->post('category-id'))
			->data('name', $this->request->post('name'))
			->data('description', $this->request->post('description'))
			->data('price', $this->request->post('price'))
			->data('available_count', $this->request->post('available-count'))
			->data('status', $this->request->post('status'))
			->insert($this->table)->lastId();

		for ($i = 0; $i < $countFiles; $i++) {
			$this
				->data('product_id', $id)
				->data('name', $images[$i])
				->insert($this->imagesTable);
		}
	}

	/**
	 * Get Product Related Products
	 *
	 * @return array
	 */
	public function getProductRelatedProducts($id)
	{
		if (!$this->app->isSharing('productRelatedProducts')) {

			$categoryId = $this->get($id)->category_id;

			$products = $this->select('p.id, p.price, p.name, p.description, p.available_count, p.rating, p.raters_count, pi.name image')
				->from($this->table . ' p')
				->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
				->where('p.category_id = ? AND p.status = ? AND p.id != ?', $categoryId, 1, $id)
				->limit(20)
				->fetchAll();

			$this->app->share('productRelatedProducts', $products);
		}

		return $this->app->get('productRelatedProducts');
	}

	/**
	 * paginate products
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

		return $this->select('p.id, p.name, c.id category_id, c.name category_name, p.description, p.price, p.available_count, p.status')
			->from($this->table . ' p')
			->join('LEFT JOIN categories c ON p.category_id = c.id')
			->orderBy('id')
			->limit($limit, $offset)
			->fetchAll();
	}

	/**
	 * paginate searched products
	 *
	 * @var int category id
	 * @var String order by
	 * @var String sort by
	 * @return array
	 */
	public function paginateSearchedProducts($categoryId, $keyword, $orderBy, $sortBy)
	{
		// We Will get the current page
		$currentPage = $this->pagination->page();
		// We Will get the items Per Page
		$limit = $this->pagination->itemsPerPage();
		// Set our offset
		$offset = $limit * ($currentPage - 1);

		
		if (empty($categoryId) || $categoryId == 'null') {
			$result = $this->select('COUNT(id) AS `total`')
					->from($this->table)
					->where('status = ? AND name LIKE ' . "'%$keyword%'", 1)
					->fetchAll();

					//set total items
					$this->pagination->setTotalItems($result[0]->total);
					
			$products = $this->select('p.id, p.price, d.price discounted_price, p.name, p.available_count, p.rating, p.raters_count, pi.name image')
				->from($this->table . ' p')
				->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
				->join('LEFT JOIN deals d ON d.product_id = p.id')
				->where('p.status = ? AND p.name LIKE ' . "'%$keyword%'", 1)
				->orderBy('p.' . $orderBy, $sortBy)
				->limit($limit, $offset)
				->fetchAll();
		} else {
			$result = $this->select('COUNT(id) AS `total`')
			->from($this->table)
			->where('category_id = ? AND status = ? AND name LIKE' . "'%$keyword%'", $categoryId, 1)
			->fetchAll();

			//set total items
			$this->pagination->setTotalItems($result[0]->total);

			$products = $this->select('p.id, p.price, d.price discounted_price, p.name, p.available_count, p.rating, p.raters_count, pi.name image')
				->from($this->table . ' p')
				->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
				->join('LEFT JOIN deals d ON d.product_id = p.id')
				->where('p.category_id = ? AND p.status = ? AND p.name LIKE' . "'%$keyword%'", $categoryId, 1)
				->orderBy('p.' . $orderBy, $sortBy)
				->limit($limit, $offset)
				->fetchAll();
		}

		return $products;
	}

	/**
	 * paginate category products
	 *
	 * @var int category id
	 * @var String order by
	 * @var String sort by
	 * @return array
	 */
	public function paginateCategoryProducts($categoryId, $orderBy, $sortBy)
	{
		//pred($orderBy . $s);
		// We Will get the current page
		$currentPage = $this->pagination->page();
		// We Will set the items Per Page
		$this->pagination->setItemsPerPage(10);
		// We Will get the items Per Page
		$limit = $this->pagination->itemsPerPage();
		// Set our offset
		$offset = $limit * ($currentPage - 1);
		//set total items
		$this->pagination->setTotalItems($this->count($this->table, 'category_id', $categoryId));

		return $this->select('p.id, p.price, d.price discounted_price, p.name, p.available_count, p.rating, p.raters_count, pi.name image')
			->from($this->table . ' p')
			->join('LEFT JOIN deals d ON d.product_id = p.id')
			->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
			->where('p.category_id = ? AND p.status = ?', $categoryId, 1)
			->limit($limit, $offset)
			->orderBy('p.' . $orderBy, $sortBy)
			->fetchAll();
	}

	/**
	 * get product images
	 *
	 * @return array
	 */
	public function getProductImages($id)
	{
		return $this->select('name')
			->from($this->imagesTable)
			->where('product_id = ?', $id)
			->fetchAll();
	}

	/**
	 * search in products
	 *
	 * @var string $value
	 * @return array
	 */
	public function search($value)
	{
		// We Will get the current page
		$currentPage = $this->pagination->page();
		// We Will get the items Per Page
		$limit = $this->pagination->itemsPerPage();
		// Set our offset
		$offset = $limit * ($currentPage - 1);
		//set total items
		$this->pagination->setTotalItems($this->count());

		return $this->select('p.id, p.name, c.id category_id, c.name category_name, p.description, p.price, p.available_count, p.status')
			->from($this->table . ' p')
			->join('LEFT JOIN categories c ON p.category_id = c.id')
			->where('p.name = ? OR p.id = ? OR c.id = ? OR c.parent_id = ?', $value, $value, $value, $value)
			->orderBy('id')
			->limit($limit, $offset)
			->fetchAll();
	}

	/**
	 * Update Product Record By Id
	 *
	 * @param int $id
	 * @return void
	 */
	public function update($id)
	{
		$this
			->data('category_id', $this->request->post('category-id'))
			->data('name', $this->request->post('name'))
			->data('description', $this->request->post('description'))
			->data('price', $this->request->post('price'))
			->data('available_count', $this->request->post('available-count'))
			->data('status', $this->request->post('status'))
			->where('id=?', $id)
			->update($this->table);

		$countFiles = count($_FILES);

		if ($countFiles <= 0) return;

		$oldImages = $this->getProductImages($id);
		$oldImagesCount = count($oldImages);

		for ($i = 0; $i < $oldImagesCount; $i++) {
			$this->deleteImage($oldImages[$i]->name);
		}

		$this->where('product_id = ?', $id)->delete($this->imagesTable);

		$images = [];

		for ($i = 0; $i < $countFiles; $i++) {
			$images[] = $this->uploadImage("product-image-$i");
		}

		for ($i = 0; $i < $countFiles; $i++) {
			$this
				->data('product_id', $id)
				->data('name', $images[$i])
				->insert($this->imagesTable);
		}
	}

	/**
	 * delete Product Record By Id
	 *
	 * @param int $id
	 * @return void
	 */
	public function delete($id, $table = null)
	{
		$oldImages = $this->getProductImages($id);
		$oldImagesCount = count($oldImages);

		for ($i = 0; $i < $oldImagesCount; $i++) {
			$this->deleteImage($oldImages[$i]->name);
		}

		$this->where('id = ?', $id)->delete($this->table);
	}

	/**
	 * delete Image
	 *
	 * @return bool
	 */
	private function deleteImage($imageName)
	{
		$path = $this->app->file->toPublic('common/images/') . $imageName;

		if (file_exists($path)) {
			return unlink($path);
		}
	}

	/**
	 * Upload Image
	 *
	 * @return string
	 */
	private function uploadImage($input)
	{
		$image = $this->request->file($input);

		if (!$image->exists()) {
			return '';
		}

		return $image->moveTo($this->app->file->toPublic('common/images'));
	}

	/**
	 * update product status
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
