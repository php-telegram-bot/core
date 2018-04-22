<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use Illuminate\Config\Repository;

class Config extends Repository
{
    /**
     * Add a single custom commands path
     *
     * @param string $path Custom commands path to add
     * @param bool $before If the path should be prepended or appended to the list
     *
     * @return void
     */
    public function addCommandsPath($path, $before = true)
    {
        $paths = $this->get('commands.paths', []);

        if (! is_dir($path)) {
            Logger::error('Commands path "%s" does not exist.', $path);
        } else if (! in_array($path, $paths, true)) {
            if ($before) {
                array_unshift($paths, $path);
            } else {
                $paths[] = $path;
            }
        }

        $this->set('commands.paths', $paths);
    }

    /**
     * Add multiple custom commands paths
     *
     * @param array $paths Custom commands paths to add
     * @param bool $before If the paths should be prepended or appended to the list
     *
     * @return void
     */
    public function addCommandsPaths(array $paths, $before = true)
    {
        foreach ($paths as $path) {
            $this->addCommandsPath($path, $before);
        }
    }

    /**
     * Return the list of commands paths
     *
     * @return array
     */
    public function getCommandsPaths()
    {
        return $this->get('commands.paths', []);
    }

    /**
     * Enable a single Admin account
     *
     * @param integer $admin_id Single admin id
     *
     * @return void
     */
    public function addAdmin($admin_id)
    {
        $admins = $this->get('admins', []);

        if (! is_int($admin_id) || $admin_id <= 0) {
            Logger::error('Invalid value "%s" for admin.', $admin_id);
        } else if (! in_array($admin_id, $admins, true)) {
            $admins[] = $admin_id;
        }

        $this->set('admins', $admins);
    }

    /**
     * Enable a list of Admin Accounts
     *
     * @param array $admin_ids List of admin ids
     *
     * @return void
     */
    public function addAdmins(array $admin_ids)
    {
        foreach ($admin_ids as $admin_id) {
            $this->addAdmin($admin_id);
        }
    }

    /**
     * Get list of admins
     *
     * @return array
     */
    public function getAdmins()
    {
        return $this->get('admins', []);
    }

    /**
     * Set custom upload path
     *
     * @param string $path Custom upload path
     *
     * @return void
     */
    public function setUploadPath($path)
    {
        $this->set('upload_path', $path);
    }

    /**
     * Get custom upload path
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->get('upload_path', '');
    }

    /**
     * Set custom download path
     *
     * @param string $path Custom download path
     *
     * @return void
     */
    public function setDownloadPath($path)
    {
        $this->set('download_path', $path);
    }

    /**
     * Get custom download path
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return $this->get('download_path', '');
    }
}
