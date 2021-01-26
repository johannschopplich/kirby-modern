<?php

namespace D4L\StaticSite;

class Media
{
    protected static $_instance;
    protected $_active = false;
    protected $_list = [];

    public static function getInstance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    public static function register($root, $url)
    {
        $instance = static::getInstance();
        $item = [
            'root' => $root,
            'url' => $url
        ];

        if (in_array($item, $instance->_list)) {
            return;
        }

        $instance->_list[] = $item;
    }

    public static function getList()
    {
        $instance = static::getInstance();
        return $instance->_list;
    }

    public static function clearList()
    {
        $instance = static::getInstance();
        $instance->_list = [];
    }

    public static function isActive()
    {
        $instance = static::getInstance();
        return $instance->_active;
    }

    public static function setActive(bool $active)
    {
        $instance = static::getInstance();
        $instance->_active = $active;
    }
}
