<?php

namespace App\Models;

use System\Model;

class SettingsModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Loaded Settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Load And Store All settings in the database
     *
     * @return void
     */
    public function settings()
    {
        if (empty($this->settings)) {

            $settings = $this->all();
            foreach ($settings as $setting) {
                $this->settings[$setting->key] = $setting->value;
            }
        }

        return $this->settings;
    }

    /**
     * Get Settings By Key
     *
     * @param string $key
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($this->settings, $key);
    }

    /**
     * Update Settings
     *
     * @return void
     */
    public function update()
    {
        // pre-defined keys (settings) that will be stored in database
        $keys = ['nav_announcement', 'site_name', 'site_email', 'site_status', 'site_close_msg'];

        foreach ($keys as $key) {
            $this->where('`key` = ?', $key)->delete($this->table);
            $this->data('key', $key)
                ->data('value', $this->request->post(\str_replace('_', '-', $key)))
                ->insert($this->table);
        }
    }
}
