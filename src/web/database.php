<?php

require '../../vendor/autoload.php';
require_once 'classes/image.class.php';

use MongoDB\BSON\ObjectID;

class database
{
    private static function get_db()
    {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_w3b',
            ]
        );

        $db = $mongo->wai;

        return $db;
    }

    /**
     * @return Image[]
     */
    private static function getImagesBy($conditions, $page = 0, $pageSize = -1)
    {
        $options = database::prepareOptionsForGetImagesBy($page, $pageSize);

        $db = database::get_db();
        $imageDocuments = $db->images->find($conditions, $options);

        $images = [];
        foreach ($imageDocuments as $imageDocument) {
            $images[] = database::createImageFromImageDocument($imageDocument);
        }

        return $images;
    }

    private static function prepareOptionsForGetImagesBy($page, $pageSize)
    {
        $options = [];
        if ($pageSize > 0) {
            $options = [
                'limit' => $pageSize,
                'skip' => $page * $pageSize,
            ];
        }
        return $options;
    }

    private static function createImageFromImageDocument($imageDocument)
    {
        return new Image(
            $imageDocument['title'],
            $imageDocument['author'],
            $imageDocument['ownerID'],
            $imageDocument['_id'],
            $imageDocument['private']
        );
    }

    public static function createID($id = null)
    {
        return new ObjectID($id);
    }

    public static function getImageByID($id)
    {
        $db = database::get_db();
        $db->images->findOne(['_id' => database::createID($id)]);
    }

    public static function getImagesByUser($ownerID)
    {
        return database::getImagesBy(['ownerID' => $ownerID]);
    }

    public static function getPublicImages($page = 0, $pageSize = -1)
    {
        return database::getImagesBy(['private' => false], $page, $pageSize);
    }

    /**
     * @param Image $image
     */
    public static function saveImageToDB($image)
    {
        $db = database::get_db();
        $db->images->insertOne($image);
    }

    /**
     * @brief Deletes ONLT THE ENTRY IN THE DATABASE, not the file
     */
    public static function deleteImageByID($id)
    {
        $db = database::get_db();
        $db->images->deleteOne(['_id' => database::createID($id)]);
    }

    public static function deleteAllImages()
    {
        $images = database::getImagesByUser(null);
        foreach ($images as $image) {
            $image->delete();
        }
    }

    /**
     * @param string $username
     */
    public static function checkIfUserExists($username)
    {
        $db = database::get_db();
        $userDocument = $db->users->findOne(['username' => $username]);

        return $userDocument != null;
    }

    /**
     * @param User $user
     */
    public static function createUser($user)
    {
        if (database::checkIfUserExists($user->username)) {
            return false;
        }
        $db = database::get_db();
        $db->users->insertOne($user);
        return true;
    }

    public static function getUserByUsername($username)
    {
        $db = database::get_db();
        $userDocument = $db->users->findOne(['username' => $username]);
        $user = new User(
            $userDocument['_id'],
            $userDocument['username'],
            $userDocument['email'],
            $userDocument['hashedPassword']
        );

        return $user;
    }

    public static function getAllUsers()
    {
        $db = database::get_db();
        return $db->users->find();
    }

    public static function deleteAllUsers()
    {
        $db = database::get_db();
        $db->users->deleteMany([]);
    }
}
