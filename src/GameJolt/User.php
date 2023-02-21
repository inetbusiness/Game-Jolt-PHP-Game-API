<?php
namespace GameJolt;

/**
 * User
 * Ported straight from the Java API into PHP.
 *
 * @author Ashley Gwinnell, Sven Donner
 * @version 1.0
 */
class User implements GJUser
{
    /** The User properties */
    private $properties;

    /**
     * User constructor.
     */
    public function __construct() {
        $this->properties = array();
    }

    /**
     * @param $key
     * @param $value
     */
    public function addProperty($key, $value) {
        $this->properties[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public function getProperty($key) {
        return $this->properties[$key];
    }

    /**
     * @return mixed|string
     */
    public function getId() {
        return $this->getProperty("id");
    }

    /**
     * @return mixed|string
     */
    public function getUsername() {
        return $this->getProperty("username");
    }

    /**
     * @return mixed|string
     */
    public function getToken() {
        return $this->getProperty("token");
    }

    /**
     * @return mixed|string
     */
    public function getAvatarURL() {
        return $this->getProperty("avatar_url");
    }

    /**
     * @return string
     */
    public function toString() {
        return "{id=" . $this->getId() . ", title=" . $this->getUsername() . "}";
    }
}