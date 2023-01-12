<?php

namespace App\Models;

use System\Model;

class OffersModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'offers';

    /**
     * users count
     *
     * @var int
     */
    protected $count;

    /**
     * Slider Table name
     *
     * @var string
     */
    private $sliderTable = 'slider_images';

    /**
     * Slider Table name
     *
     * @var string
     */
    private $dealsTable = 'deals';

    /**
     * get enabled slider records
     *
     * @return array
     */
    public function getEnabledSliderImages()
    {
        return $this->select('id, name image')
            ->from($this->sliderTable())
            ->where('status = ?', 1)
            ->orderBy('`order`')
            ->fetchAll();
    }

    /**
     * get enabled deals records
     *
     * @return array
     */
    public function getEnabledDeals()
    {
        return $this->select('d.id, d.product_id, d.price discounted_price, p.name, p.description, p.price, p.rating, p.raters_count, pi.name image, p.available_count')
            ->from($this->dealsTable . ' d')
            ->join('LEFT JOIN products p ON d.product_id = p.id')
            ->join('LEFT JOIN products_images pi ON pi.name = (SELECT pi.name FROM products_images pi WHERE pi.product_id = p.id LIMIT 1)')
            ->where('d.status = ? AND p.status = ?', 1, 1)
            ->fetchAll();
    }

    /**
     * Create New Slider Record
     *
     * @return void
     */
    public function createSlider()
    {
        $image = $this->uploadImage('slider-image');

        if (empty($image)) return;

        $count = $this->select('COUNT(id) AS `total`')->fetch($this->sliderTable)->total;

        $this
            ->data('name', $image)
            ->data('order', intval($count) + 1)
            ->data('status', 0)
            ->insert($this->sliderTable);
    }

    /**
     * Create New Deal Record
     *
     * @return void
     */
    public function createDeal($dealData)
    {
        $this
            ->data('product_id', $dealData['product-id'])
            ->data('price', $dealData['price'])
            ->data('status', $dealData['status'])
            ->insert($this->dealsTable());
    }

    /**
     * get deals records
     *
     * @return array
     */
    public function getDeals()
    {
        return $this->select('d.id, d.product_id, d.status, d.price discounted_price, p.name, p.price, p.available_count')
            ->from($this->dealsTable . ' d')
            ->join('LEFT JOIN products p ON d.product_id = p.id')
            ->fetchAll();
    }

    /**
     * get deal
     *
     * @return array
     */
    public function getDeal($dealId)
    {

        return $this
            ->select('d.id, d.product_id, d.price discounted_price, p.name, p.description, p.price, p.rating, p.raters_count, p.available_count')
            ->from($this->dealsTable . ' d')
            ->join('LEFT JOIN products p ON d.product_id = p.id')
            ->where('d.id = ?', $dealId)
            ->fetch();
    }

    /**
     * delete Slider Record By Id
     *
     * @param int $id
     * @return void
     */
    public function deleteSlider($id)
    {
        $sliderCount = $this->count($this->sliderTable());
        $itemOrder = $this->get($id, $this->sliderTable())->order;

        $this->delete($id, $this->sliderTable());

        if ($sliderCount == $itemOrder) return;

        $editItemsCount = $sliderCount - $itemOrder;

        for ($i = 0; $i < $editItemsCount; $i++) {
            $this
                ->data('order', $itemOrder + $i)
                ->where('`order` = ?', $itemOrder + $i + 1)
                ->update($this->sliderTable());
        }
    }

    /**
     * update record status
     *
     * @param int $id
     * @param int $status
     * @return void
     */
    public function updateStatus($id, $status, $table)
    {
        $this
            ->data('status', $status)
            ->where('id=?', $id)
            ->update($table);
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
     * slider table getter
     *
     * @param int $id
     * @param int $status
     * @return void
     */
    public function sliderTable()
    {
        return $this->sliderTable;
    }

    /**
     * deals table getter
     *
     * @param int $id
     * @param int $status
     * @return void
     */
    public function dealsTable()
    {
        return $this->dealsTable;
    }
}
