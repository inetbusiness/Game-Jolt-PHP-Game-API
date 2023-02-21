<?php
namespace GameJolt;

interface GJUser
{
    /**
     * Create a new User.
     */
    public function __construct();

    /**
     * Adds a property to the User.
     * @param string key The key by which the property can be accessed.
     * @param string value The value for the key.
     */
    public function addProperty($key, $value);

    /**
     * Gets a property of the User that isn't specified by a specific method.
     * This exists for forward compatibility.
     * @param string key The key of the User attribute you want to obtain.
     * @return string A property of the User that isn't specified by a specific method.
     */
    public function getProperty($key);

    /**
     * Get the ID of the User.
     * @return string The ID of the Trophy.
     */
    public function getId();

    /**
     * Get the Username of the User.
     * @return string The ID of the Trophy.
     */
    public function getUsername();

    /**
     * Get the Username of the User.
     * @return string The ID of the Trophy.
     */
    public function getToken();


    /**
     * Get the Username of the User.
     * @return string The ID of the Trophy.
     */
    public function getAvatarURL();

    public function toString();
}