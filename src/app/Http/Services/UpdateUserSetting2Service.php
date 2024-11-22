<?php

namespace App\Http\Services;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateUserSetting2Service
{
    private $settings;
    private $autoCommit;
    private $currentUser;
    private static $instance;

    public function __construct($autoCommit = true)
    {
        $this->currentUser = CurrentUser::get();
        if (!$this->currentUser) {
            throw new \Exception("Current user not found.");
        }
        $this->settings = $this->currentUser->settings;
        $this->autoCommit = $autoCommit;
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->settings, $key, $default);
    }

    public function set($key, $value)
    {
        if (empty($key) || !is_string($key)) {
            throw new \InvalidArgumentException("Invalid key provided.");
        }
        Arr::set($this->settings, $key, $value);
        return $this;
    }

    public function inc($key, $valueBy = 1)
    {
        if (empty($key) || !is_string($key)) {
            throw new \InvalidArgumentException("Invalid key provided.");
        }
        $value = Arr::get($this->settings, $key, 0);
        $value += ($valueBy * 1);
        Arr::set($this->settings, $key, $value);
        return $this;
    }

    public function pushToSet($key, $valueArray)
    {
        $valueArray = is_array($valueArray) ? $valueArray : [$valueArray];
        $array = Arr::get($this->settings, $key, []);
        if (is_array($array)) {
            $array = array_unique(array_merge($array, $valueArray));
            Arr::set($this->settings, $key, array_values($array));
        }
        return $this;
    }

    public function unsetFromSet($key, $valueArray)
    {
        $valueArray = is_array($valueArray) ? $valueArray : [$valueArray];
        $array = Arr::get($this->settings, $key, []);
        if (is_array($array)) {
            $array = array_diff($array, $valueArray);
            if (count($array) > 0) {
                Arr::set($this->settings, $key, array_values($array));
                // } else {
                //     Arr::forget($this->settings, $key);
            }
        }
        return $this;
    }

    public function commit()
    {
        try {
            if ($this->currentUser) {
                $this->currentUser->settings = $this->settings;
                $this->currentUser->save();
            }
        } catch (\Exception $e) {
            Log::error("Failed to commit user settings: " . $e->getMessage());
        }
        return $this;
    }

    public function __toString()
    {
        return json_encode($this->settings);
    }

    public function __destruct()
    {
        if ($this->autoCommit) {
            $this->commit();
        }
    }

    public static function getInstance($autoCommit = true)
    {
        if (!self::$instance) {
            self::$instance = new UpdateUserSetting2Service($autoCommit);
        }
        return self::$instance;
    }
}
