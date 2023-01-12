<?php

namespace App\Models;

use System\Model;

class LanguagesModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * Create New Category Record
     *
     * @return bool
     */
    public function create()
    {
        $file = $this->uploadFile('language-file', $this->request->post('code'));

        if (empty($file)) return false;

        $this->data('name', $this->request->post('name'))
            ->data('code', $this->request->post('code'))
            ->data('status', $this->request->post('status'))
            ->data('file', $file)
            ->insert($this->table);

        return true;
    }

    /**
     * Get enabled languages with total number of posts for each category
     *
     * @return array
     */
    public function getEnabledLanguages()
    {
        // share the languages in the application to not call it twice in same request

        if (!$this->app->isSharing('enabled-languages')) {
            // first we will get the enabled languages
            // and we will add another condition that total number of posts
            // for each category
            // should be more than zero
            $languages = $this->db->select('*')
                ->from($this->table)
                ->where('status=?', 1)
                ->fetchAll();

            $this->app->share('enabled-languages', $languages);
        }

        return $this->app->get('enabled-languages');
    }

    /**
     * Delete Record By Id
     *
     * @param int $id
     * @return void
     */
    public function delete($id, $table = null)
    {
        $filePath = $this->get($id)->file;

        $success = $this->deleteFile($filePath);

        if (!$success) return false;

        $this->where('id = ?', $id)->delete($table ?: $this->table);

        return true;
    }


    /**
     * Update Category Record By Id
     *
     * @param int $id
     * @return bool
     */
    public function update($id)
    {
        $code = $this->request->post('code');

        if (!empty($_FILES['language-file']['full_path'])) {

            $file = $this->get($id)->file;

            $success = $this->deleteFile($file);

            if (!$success) return false;

            $file = $this->uploadFile('language-file', $code);

            if (empty($file)) return false;

            $this->data('file', $file);
        } else {

            $file = $this->get($id)->file;

            $success = $this->renameFile($file, $code . '.json');

            if (!$success) return false;

            $this->data('file', $code . '.json');
        }

        $this->data('name', $this->request->post('name'))
            ->data('code', $code)
            ->data('status', $this->request->post('status'))
            ->where('id=?', $id)
            ->update($this->table);

        return true;
    }

    /**
     * update status
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
     * Rename file
     *
     * @return bool
     */
    private function renameFile($old, $new)
    {
        return rename($this->file->toPublic('common/i18n/' . $old), $this->file->toPublic('common/i18n/' . $new));
    }

    /**
     * Upload file
     *
     * @return string
     */
    private function uploadFile($input, $name)
    {
        $file = $this->request->file($input);

        if (!$file->exists()) {
            return '';
        }

        return $file->moveTo($this->app->file->toPublic('common/i18n'), $name);
    }

    /**
     * delete file
     *
     * @return bool
     */
    private function deleteFile($filePath)
    {
        $path = $this->app->file->toPublic('common/i18n/') . $filePath;

        if (file_exists($path)) {
            return unlink($path);
        }
    }
}
