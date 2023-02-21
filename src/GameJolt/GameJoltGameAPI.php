<?php


namespace GameJolt;


interface GameJoltGameAPI
{
    /**
     * Create a new GameJoltAPI with out verifiying the user.
     * You should call verifyUser(username, usertoken) to verify the user.
     * @param int game_id Your Game's Unique ID.
     * @param string private_key Your Game's Unique (Private) Key.
     */
    public function __construct($game_id, $private_key);

    /**
     * Set whether the script should use cURL. It does not use cURL by default.
     * @param bool Whether the script should use cURL or not.
     */
    public function setUsingCURL($bool);

    /**
     * Set the version of the GameJolt API to use.
     * @param string version The version of the GameJolt API to be using.
     */
    public function setVersion($version);

    /**
     * Get the version of the GameJolt API you are using.
     * Current API Version is 1.
     * @return string The API version in use.
     */
    public function getVersion();

    /**
     * Check whether the user/player has verified their credentials.
     * @return boolean whether the user/player has verified their credentials or not.
     */
    public function isVerified();

    /**
     * Sets whether the API should print out debug information to the Console.
     * By default, this is set to true.
     * @param boolean b whether the API should print out debug informationto the Console.
     */
    public function setVerbose($b);

    /**
     * Give the currently verified user a trophy specified by ID.
     * This method uses the trophy's ID.
     * @param string trophy_id The ID of Trophy to give.
     * @return boolean true on successfully given trophy.
     */
    public function achieveTrophy($trophy_id);

    /**
     * Get a list of trophies filtered with the Achieved parameter.
     * The parameter can be "TRUE" for achieved trophies, "FALSE" for
     * unachieved trophies or "EMPTY" for all trophies.
     * @param string a The type of trophies to get.
     * @return Trophy[] A list of trophy objects.
     */
    public function getTrophies($type);

    /**
     * Gets a single trophy from GameJolt as specified by trophyId
     * @param string trophyId The ID of the Trophy you want to get.
     * @return Trophy The Trophy Object with the ID passed.
     */
    public function getTrophy($trophy_id);

    /**
     * @return User
     */
    public function getVerifiedUser();

    /**
     * Attempt to verify the Players Credentials.
     * @param string username The Player's Username.
     * @param string userToken The Player's User Token.
     * @return boolean true if the User was successfully verified, false otherwise.
     */
    public function verifyUser($username, $usertoken);

    /**
     * Perform a GameJolt API request.
     * Use this one if you know your HTTP requests.
     * @param string method The API method to call. Note that gamejolt.com/api/game/ is already prepended.
     * @param string paramsLine The GET request params, such as "trophy_id=23&achieved=empty".
     * @return array The response, default is keypair.
     */
    public function request($method, $paramsline);

    /**
     * Make a request to the GameJolt API.
     * @param string method The GameJolt API method, such as "add-trophy", without the "game-api/" part.
     * @param array params A map of the parameters you want to include.
     * 				 Note that if the user is verified you do not have to include the username/user_token/game_id.
     * @param boolean requireVerified This is only set to false when checking if the user is verified.
     * @return string HTTP Response
     */
    public function requestFromArray($method, $params, $require_verified);
}