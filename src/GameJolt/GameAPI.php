<?php

namespace GameJolt;

/**
 * GameJoltTrophyAPI
 * Ported straight from the Java API into PHP.
 *
 * @version 1.0
 * @Author: Ashley Gwinnell, Sven Donner
 * @Copyright: Ashley Gwinnell (https://opensource.org/licenses/MIT)
 * @Project: Framework
 * @Year: 2010
 */
class GameAPI implements GameJoltGameAPI
{
    private $protocol = "http://";
    private $api_root = "gamejolt.com/api/game/";

    private $game_id;
    private $private_key;
    private $version;

    private $username;
    private $usertoken;

    private $verbose = false;
    private $verified = false;
    private $using_curl = false;

    /**
     * GameAPI constructor.
     * @param $game_id
     * @param $private_key
     */
    public function __construct($game_id, $private_key)
    {
        $this->game_id = $game_id;
        $this->private_key = $private_key;
        $this->version = 1;
    }

    /**
     * @param $bool
     */
    public function setUsingCURL($bool)
    {
        $this->using_curl = $bool;
    }

    /**
     * @return int|string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param $b
     */
    public function setVerbose($b)
    {
        $this->verbose = $b;
    }

    /**
     * @param $trophy_id
     * @return bool
     */
    public function achieveTrophy($trophy_id)
    {
        $response = $this->request("trophies/add-achieved", "trophy_id=" . $trophy_id);
        if (strpos($response, "success:\"true\"") !== FALSE) {
            return true;
        } else {
            if ($this->verbose) {
                echo "GameJoltAPI: Could not give Trophy to user.<br/>\n";
                echo $response . "<br/>";
            }
            return false;
        }
    }

    /**
     * @param $method
     * @param $paramsline
     * @return array|string
     */
    public function request($method, $paramsline)
    {
        $array = array();
        $params = explode("&", $paramsline);
        for ($i = 0; $i < count($params); $i++) {
            if (strlen($params[$i]) == 0) {
                continue;
            }
            $s = explode("=", $params[$i]);
            $key = $s[0];
            $value = $s[1];
            $array[$key] = $value;
        }
        return $this->requestFromArray($method, $array, true);
    }

    /**
     * @param $method
     * @param $params
     * @param $require_verified
     * @return string
     */
    public function requestFromArray($method, $params, $require_verified)
    {
        if ($require_verified && !$this->verified) {
            return "REQUIRES_AUTHENTICATION";
        }

        if (!$this->verified) {
            $user_token = $params['user_token'];
            $params['user_token'] = $user_token . $this->private_key;
            $urlString = $this->getRequestURL($method, $params);
            $signature = md5($urlString);
            $params['user_token'] = $user_token;
            $params['signature'] = $signature;
        } else {
            $params['user_token'] = $this->usertoken . $this->private_key;
            $params['username'] = $this->username;
            $urlString = $this->getRequestURL($method, $params);
            $signature = md5($urlString);

            $params['user_token'] = $this->usertoken;
            $params['signature'] = $signature;
        }

        $urlString = $this->getRequestURL($method, $params);
        if ($this->verbose) {
            echo "urlString: " . $urlString . "<br/>";
        }
        return $this->openURLAndGetResponse($urlString);
    }

    /**
     * Get the full request url from the parameters given.
     * @param string method The GameJolt API method, such as "game-api/add-trophy".
     * @param array params A map of the parameters you want to include.
     * @return string The full request url.
     */
    private function getRequestURL($method, $params)
    {
        $urlString = $this->protocol . $this->api_root . "v" . $this->version . "/" . $method . "?game_id=" . $this->game_id;
        $user_token = "";
        foreach ($params as $key => $value) {
            if ($key == "user_token") {
                $user_token .= $value;
                continue;
            }
            $urlString .= "&" . $key . "=" . $value;
        }
        $urlString .= "&user_token=" . $user_token;
        return $urlString;
    }

    /**
     * Performs the HTTP Request using either CURL or file functions.
     * @param string urlString The URL to HTTP Request.
     * @return string The HTTP Response.
     */
    private function openURLAndGetResponse($url)
    {
        if ($this->using_curl) {
            $str = curl_get_contents($url);
        } else {
            $str = file_get_contents($url);
        }
        return $str;
    }

    /**
     * @param $type
     * @return array
     */
    public function getTrophies($type)
    {
        $trophies = array();
        $response = $this->request("trophies/", "achieved=" . strtolower($type));

        $lines = explode("\n", $response);
        $t = new Trophy();
        for ($i = 1; $i < count($lines); $i++) {
            $key = substr($lines[$i], 0, strpos($lines[$i], ":")); // from start until colon.
            $value = substr($lines[$i], strpos($lines[$i], ":") + 2, strrpos($lines[$i], '"')); // after colon and inverted comma until last inverted comma
            if ($key == "id") {
                $t = new Trophy();
            }
            $t->addProperty($key, $value);
            if ($key == "achieved") {
                $trophies[] = $t;
            }
        }
        return $trophies;
    }

    /**
     * @param $trophy_id
     * @return Trophy|null
     */
    public function getTrophy($trophy_id)
    {
        $response = $this->request("trophies/", "trophy_id=" . $trophy_id);
        if (strpos($response, "success:\"true\"") === FALSE) {
            if ($this->verbose) {
                echo "GameJoltAPI: Could not get Trophy with Id " . $trophy_id . ".<br/>";
            }
            return null;
        }
        $lines = explode("\n", $response);
        print_r($lines);
        echo $lines[1], strpos('"', $lines[1]) + 1;
        $t = new Trophy();
        $t->addProperty("id", substr($lines[1], strpos($lines[1], '"') + 1, strrpos($lines[1], '"') - strpos($lines[1], '"') - 1));
        $t->addProperty("title", substr($lines[2], strpos($lines[2], '"') + 1, strrpos($lines[2], '"') - strpos($lines[2], '"') - 1));
        $t->addProperty("description", substr($lines[3], strpos($lines[3], '"') + 1, strrpos($lines[3], '"') - strpos($lines[3], '"') - 1));
        $t->addProperty("difficulty", strtoupper(substr($lines[4], strpos($lines[4], '"') + 1, strrpos($lines[4], '"') - strpos($lines[4], '"') - 1)));
        $t->addProperty("image_url", substr($lines[5], strpos($lines[5], '"') + 1, strrpos($lines[5], '"') - strpos($lines[5], '"') - 1));
        $t->addProperty("achieved", substr($lines[6], strpos($lines[6], '"') + 1, strrpos($lines[6], '"') - strpos($lines[6], '"') - 1));
        return $t;
    }

    /**
     * @return User|null
     */
    public function getVerifiedUser()
    {
        if ($this->verified == false) {
            return new User();
        }
        $response = $this->request("users/", "username=" . $this->username);
        if (strpos($response, "success:\"true\"") === FALSE) {
            if ($this->verbose) {
                echo "GameJoltGameAPI: Could not get User with User " . $this->username . ".<br/>";
            }
            return null;
        }
        $lines = explode("\n", $response);
        $user = new User();
        for ($i = 1; $i < count($lines); $i++) {
            $key = substr($lines[$i], 0, strpos($lines[$i], ":")); // from start until colon.
            $value = substr($lines[$i], strpos($lines[$i], ":") + 2, strrpos($lines[$i], '"')); // after colon and inverted comma until last inverted comma
            $user->addProperty($key, substr($value, 0, strlen($value) - 2));
        }
        $user->addProperty("token", $this->usertoken);
        return $user;
    }

    /**
     * @param $username
     * @param $usertoken
     * @return bool
     */
    public function verifyUser($username, $usertoken)
    {
        $this->verified = false;
        $params = array();
        $params['username'] = $username;
        $params['user_token'] = $usertoken;
        $response = $this->requestFromArray("users/auth/", $params, false);
        if ($this->verbose) {
            echo "Response from verifyUser(): " . $response . "<br/>";
        }
        $lines = explode("\n", $response);
        foreach ($lines as $key => $line) {
            $ls = explode(":", $line);
//            $ls2 = strlen($ls[1]) - 2; // not in use
            $r = substr($ls[1], 1, 4);
            if ($r == "true") {
                $this->username = $username;
                $this->usertoken = $usertoken;
                $this->verified = true;
                return true;
            }
        }
        return false;
    }
}
