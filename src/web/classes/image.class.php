<?php

require_once 'database.php';
require_once 'globals.php';

class Image
{
    public $_id;
    public $title;
    public $author;
    public $ownerID;
    public $private;
    public $originalPath;
    public $thumbnailPath;
    public $watermarkPath;

    public function __construct($title, $author, $ownerID = null, $id = null, $private = false)
    {
        $this->_id = database::createID($id);
        $this->title = htmlspecialchars($title);
        $this->author = $author;
        $this->ownerID = $ownerID;
        $this->private = $private;
        $this->originalPath = IMAGE_PATH . $this->_id . '.jpg';
        $this->thumbnailPath = IMAGE_PATH . $this->_id . '_th' . '.jpg';
        $this->watermarkPath = IMAGE_PATH . $this->_id . '_wa' . '.jpg';
    }

    public function delete()
    {
        database::deleteImagebyID($this->_id);
        unlink($this->originalPath);
        unlink($this->watermarkPath);
        unlink($this->thumbnailPath);
    }
}

class uploadImage extends Image
{
    private $_image;
    public $size;
    public $type;
    // public $watermarkPath;
    public $watermarkText;
    // public $thumbnailPath;

    public function __construct($title, $watermarkText, $_image, $author, $ownerID = null, $id = null)
    {
        parent::__construct($title, $author, $ownerID, $id);
        $this->_image = $_image;
        $this->size = $_image['size'];
        $this->type = $_image['type'];
        $this->watermarkText = $watermarkText;
        $this->watermarkPath = IMAGE_PATH . $this->_id . '_wa' . '.jpg';
        $this->thumbnailPath = IMAGE_PATH . $this->_id . '_th' . '.jpg';
    }

    public function upload()
    {
        if ($this->save($this->_image) == false) {
            throw new Exception("Image upload failed");
        }

        $this->createWatermark();
        $this->createThumbnail();
    }

    public function toImage()
    {
        return new Image($this->title, $this->author, $this->ownerID, $this->_id);
    }

    private function createWatermark()
    {
        $text = $this->watermarkText;
        $image = imagecreatefromjpeg($this->originalPath);
        $textColor = imagecolorallocatealpha($image, 255, 0, 255, 20);

        $imgCenterX = imagesx($image) / 2;
        $imgCenterY = imagesy($image) / 2;

        $fontSize = imagesx($image) / strlen($text);

        $textBoundingBox = imagettfbbox($fontSize, 0, 'external/fonts/Roboto-Medium.ttf', $text);
        // bbbox[2] - x coodinate of the right bottom corner of the bounding box
        // bbbox[0] - x coodinate of the left bottom corner of the bounding box
        // so this is calcuating the with of the text in the x axis
        $textLen = $textBoundingBox[2] - $textBoundingBox[0];

        $textStartX = $imgCenterX - ($textLen / 2);
        $textStartY = $imgCenterY + ($fontSize / 2);

        imagettftext($image, $fontSize, 0, $textStartX, $textStartY, $textColor, 'external/fonts/Roboto-Medium.ttf', $this->watermarkText);

        imagejpeg($image, $this->watermarkPath);

        imagedestroy($image);
    }

    private function createThumbnail()
    {
        $image = imagecreatefromjpeg($this->watermarkPath);

        $thumbnail = imagescale($image, 200, 125);

        imagejpeg($thumbnail, $this->thumbnailPath);

        imagedestroy($image);
        imagedestroy($thumbnail);
    }

    private function save($_image)
    {
        if ($this->isCorrectType() === false) {
            return false;
        }
        if ($this->saveToDisk($_image) === false) {
            $this->saveToDB();
            return false;
        }

        $this->ifPNGconvertToJPG();
        $this->saveToDB();
        return true;
    }

    private function saveToDB()
    {
        database::saveImageToDB($this->toImage());
    }

    private function saveToDisk($_image)
    {
        if (move_uploaded_file($_image['tmp_name'], $this->originalPath)) {
            return true;
        }
        return false;
    }

    private function ifPNGconvertToJPG()
    {
        if ($this->type != 'image/png') {
            return;
        }

        $image = imagecreatefrompng($this->originalPath);
        $background = $this->createBackgroundforPNGConversion($image);

        imagecopy($background, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagejpeg($background, $this->originalPath);

        imagedestroy($image);
        imagedestroy($background);

        $this->type = 'image/jpeg';
    }

    /**
     * @brief Creates a white background that PNG images get copied onto to fix transparency issues
     * 
     * @param GdImage $image
     * @return GdImage
     */
    private function createBackgroundforPNGConversion($image)
    {
        $color = [
            'red' => 255,
            'green' => 255,
            'blue' => 255,
        ];

        $background = imagecreatetruecolor(imagesx($image), imagesy($image));

        imagefill($background, 0, 0, imagecolorallocate($background, $color["red"], $color["green"], $color["blue"]));
        imagealphablending($background, true);

        return $background;
    }

    public function isCorrectType()
    {
        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        foreach ($allowedTypes as $type) {
            if ($this->type == $type) {
                return true;
            }
        }
        return false;
    }
}
