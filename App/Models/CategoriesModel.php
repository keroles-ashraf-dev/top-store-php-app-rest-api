<?php

namespace App\Models;

use System\Model;

class CategoriesModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * users count
     *
     * @var int
     */
    protected $count;

    /**
     * Create New Category Record
     *
     * @return void
     */
    public function create()
    {
        $image = $this->uploadImage();

        if ($image) {
            $this->data('image', $image);
        }

        if (!empty($this->request->post('parent-id'))) {
            $this->data('parent_id', $this->request->post('parent-id'));
        }

        $this
            ->data('name', $this->request->post('name'))
            ->data('status', $this->request->post('status'))
            ->insert($this->table);
    }

    /**
     * Get enabled categories with total number of posts for each category
     *
     * @return array
     */
    public function getEnabledCategories()
    {
        if (!$this->app->isSharing('enabledCategories')) {
            $categories = $this->select('id, name, image')
                ->from($this->table)
                ->where('status = ?', 1)
                ->fetchAll();

            $this->app->share('enabledCategories', $categories);
        }
        return $this->app->get('enabledCategories');
    }

    /**
     * Get enabled categories with total number of posts for each category
     *
     * @return array
     */
    public function getEnabledParentCategories()
    {
        if (!$this->app->isSharing('enabledParentCategories')) {
            $categories = $this->select('id, name, image')
                ->from($this->table)
                ->where('status = ? And parent_id IS NULL', 1)
                ->limit(20)
                ->fetchAll();

            $this->app->share('enabledParentCategories', $categories);
        }
        return $this->app->get('enabledParentCategories');
    }

    /**
     * Get enabled categories with total number of posts for each category
     *
     * @return array
     */
    public function getEnabledSubCategories()
    {
        if (!$this->app->isSharing('enabledSubCategories')) {
            $categories = $this->select('id, name, image')
                ->from($this->table)
                ->where('status = ? And parent_id IS NOT NULL', 1)
                ->limit(20)
                ->fetchAll();

            $this->app->share('enabledSubCategories', $categories);
        }
        return $this->app->get('enabledSubCategories');
    }

    /**
     * Get enabled sub categories of category
     *
     * @var int category id
     * @return array
     */
    public function getSubCategoriesOfCategory($categoryId)
    {
        if (!$this->app->isSharing('enabledSubCategories')) {
            $categories = $this->select('id, name')
                ->from($this->table)
                ->where('status = ? And parent_id = ?', 1, $categoryId)
                ->fetchAll();

            $this->app->share('enabledSubCategories', $categories);
        }
        return $this->app->get('enabledSubCategories');
    }

    /**
     * Get get Product Parent Categories
     *
     * @return array
     */
    public function getProductParentCategories($id)
    {
        if (!$this->app->isSharing('productParentCategories')) {

            $categories = [];
            $category = null;
            $categoryId = $this->get($id, 'products')->category_id;

            while (!empty($categoryId)) {
                $category = $this->select('id, name, parent_id')
                    ->from($this->table)
                    ->where('id = ?', $categoryId)
                    ->fetch();

                $categories[] = $category;
                $categoryId = $category->parent_id;
            }

            $categories = array_reverse($categories);

            $this->app->share('productParentCategories', $categories);
        }

        return $this->app->get('productParentCategories');
    }

    /**
     * Get id and name of categories
     *
     * @return array
     */
    public function getCategoriesIdAndName()
    {
        // share the categories in the application to not call it twice in same request

        if (!$this->app->isSharing('categories-id-name')) {
            // first we will get the enabled categories
            // and we will add another condition that total number of posts
            // for each category
            // should be more than zero
            $categories = $this->db->select('id, name')
                ->from($this->table)
                ->fetchAll();

            $this->app->share('categories-id-name', $categories);
        }

        return $this->app->get('categories-id-name');
    }

    /**
     * paginate categories
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

        return $this->select('c.id, c.name, c.parent_id, c1.name parent_name, c.status')
            ->from($this->table . ' c')
            ->join('LEFT JOIN ' . $this->table . ' c1' . ' ON c.parent_id = c1.id')
            ->orderBy('id')
            ->limit($limit, $offset)
            ->fetchAll();
    }

    /**
     * search in categories
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

        return $this->select('c.id, c.name, c.parent_id, c1.name parent_name, c.status')
            ->from($this->table . ' c')
            ->join('LEFT JOIN ' . $this->table . ' c1' . ' ON c.parent_id = c1.id')
            ->where('c.name = ? OR c.id = ? OR c.parent_id = ?', $value, $value, $value)
            ->orderBy('id')
            ->limit($limit, $offset)
            ->fetchAll();
    }

    /**
     * Update Category Record By Id
     *
     * @param int $id
     * @return void
     */
    public function update()
    {
        $id = $this->request->post('id');

        $image = $this->uploadImage();

        $category = $this->get($id);

        if (!empty($category->image)) {
            $this->deleteImage($category->image);
        }

        if ($image) {
            $this->data('image', $image);
        }

        if (!empty($this->request->post('parent-id'))) {
            $this->data('parent_id', $this->request->post('parent-id'));
        }

        $this->data('name', $this->request->post('name'))
            ->data('status', $this->request->post('status'))
            ->where('id=?', $id)
            ->update($this->table);
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

    /**
     * Delete Record By Id
     *
     * @param int $id
     * @return void
     */
    public function delete($id, $table = null)
    {
        $filePath = $this->get($id)->image;

        $success = $this->deleteImage($filePath);

        if (!$success) return false;

        $this->where('id = ?', $id)->delete($table ?: $this->table);

        return true;
    }


    /**
     * delete Image
     *
     * @return bool
     */
    private function deleteImage($imagePath)
    {
        $path = $this->app->file->toPublic('common/images/') . $imagePath;

        if (file_exists($path)) {
            return unlink($path);
        }
    }

    /**
     * Upload Image
     *
     * @return string
     */
    private function uploadImage()
    {
        $image = $this->request->file('image');

        if (!$image->exists()) {
            return '';
        }

        return $image->moveTo($this->app->file->toPublic('common/images'));
    }
}
