<?php
namespace MythicalClient\Managers;

class SnowflakeManager
{
    /**
     * Function to generate a unique user ID
     * 
     * @return string The new user id 
     */
    private static function generateUserID()
    {
        return uniqid(true);
    }

    /**
     * Function to get the cached user IDs from a JSON file
     * 
     * @return array This will return the json file!
     */
    private static function getCachedUserIDs()
    {
        $filename = __DIR__ . '/../../caches/user_usedids.json';

        if (file_exists($filename)) {
            $json_data = file_get_contents($filename);
            $user_ids = json_decode($json_data, true);
        } else {
            $user_ids = array();
        }

        return $user_ids;
    }

    /**
     * Function to save user IDs to the cache JSON file
     * 
     * @param string $user_id Save the user id inside the cache
     * 
     * @return null Nothing this is a function
     */
    private static function saveUserIDsToCache($user_id)
    {
        $filename = __DIR__ . '/../../caches/user_usedids.json';
        $json_data = json_encode($user_id, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json_data);
    }

    /**
     * Function to check if a user ID is already used
     * 
     * @return bool If this is used or not
     */
    private static function isUserIDUsed($user_id, $user_ids)
    {
        return in_array($user_id, $user_ids);
    }

    /**
     * Function to get a unique user ID
     * 
     * @return string The user id
     */
    public static function getUniqueUserID()
    {
        $new_user_id = self::generateUserID();
        $cached_user_ids = self::getCachedUserIDs();
        while (self::isUserIDUsed($new_user_id, $cached_user_ids)) {
            $new_user_id = self::generateUserID();
        }
        $cached_user_ids[] = $new_user_id;
        self::saveUserIDsToCache($cached_user_ids);
        return $new_user_id;
    }

    /**
     * Function to delete a user ID from the cache file
     * 
     * @param string $user_id The user ID to be deleted from the cache
     * 
     * @return bool True if the user ID was successfully deleted, false otherwise
     */
    public static function deleteUserFromCache($user_id)
    {
        $cached_user_ids = self::getCachedUserIDs();

        $index = array_search($user_id, $cached_user_ids);

        if ($index !== false) {
            unset($cached_user_ids[$index]);

            self::saveUserIDsToCache($cached_user_ids);

            return true;
        }

        return false;
    }

    /**
     * Function to check if a user ID exists in the cache
     * 
     * @param string $user_id The user ID to check
     * 
     * @return bool True if the user ID exists in the cache, false otherwise
     */
    public static function doesUserExistInCache($user_id)
    {
        $cached_user_ids = self::getCachedUserIDs();

        // Check if the user ID is in the array
        return in_array($user_id, $cached_user_ids);
    }

}
?>