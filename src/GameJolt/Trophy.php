<?php
namespace GameJolt;

/**
 * Trophy is an achievement in the GameJolt API.
 * Ported straight from the Java API into PHP.
 *
 * @author Ashley Gwinnell
 * @version 0.9
 */
class Trophy
{

    /** The Trophy properties */
    private $properties;

    /**
     * Create a new Trophy.
     */
    public function __construct() {
        $this->properties = array();
    }

    /**
     * Adds a property to the Trophy.
     * @param string key The key by which the property can be accessed.
     * @param string value The value for the key.
     */
    public function addProperty($key, $value) {
        $this->properties[$key] = $value;
    }

    /**
     * Gets a property of the Trophy that isn't specified by a specific method.
     * This exists for forward compatibility.
     * @param string key The key of the Trophy attribute you want to obtain.
     * @return string A property of the Trophy that isn't specified by a specific method.
     */
    public function getProperty($key) {
        return $this->properties[$key];
    }

    /**
     * Get the ID of the Trophy.
     * @return string The ID of the Trophy.
     */
    public function getId() {
        return $this->getProperty("id");
    }
    /**
     * Get the name of the Trophy.
     * @return string The name of the Trophy.
     */
    public function getTitle() {
        return $this->getProperty("title");
    }

    /**
     * Get the description of the Trophy.
     * @return string The description of the Trophy.
     */
    public function getDescription() {
        return $this->getProperty("description");
    }

    /**
     * Get the difficulty of the Trophy.
     * i.e. Bronze, Silver, Gold, Platinum.
     * @return string The difficulty of the Trophy.
     */
    public function getDifficulty() {
        return $this->getProperty("difficulty");
    }

    /**
     * Determines whether the Trophy is achieved or not.
     * @return string True if the verified user has the Trophy.
     */
    public function isAchieved() {
        return (bool) $this->getProperty("achieved");
    }

    /**
     * Gets the URL of the Trophy's image.
     * @return string The URL of the Trophy's image.
     */
    public function getImageURL() {
        return $this->getProperty("image_url");
    }

    /**
     * @return string
     */
    public function __toString() {
        return "{id=" . $this->getId() . ", title=" . $this->getTitle() . "}";
    }
}