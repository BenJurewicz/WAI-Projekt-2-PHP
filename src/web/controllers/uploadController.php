<?php
require_once "database.php";
require_once "views/view.php";
require_once "controller.php";
require_once "classes/image.class.php";
require_once 'globals.php';

require_once "models/uploadModel.php";


class UploadController implements Controller
{
    public $uploadedImage;
    public $model;

    public function __construct()
    {
        $this->uploadedImage = null;
        $this->model = new UploadModel();
    }

    public function get()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isValidUpload()) {
                $this->prepareImage();
                $this->updateModelValues();
                if ($this->model->isCorrect) {
                    $this->uploadImage();
                }
            }
        }


        return new View("upload", $this->model);
    }

    private function updateModelValues()
    {
        if ($this->uploadedImage == null) {
            return;
        }


        // max file size defined in config (.htaccess)
        // 1 is the error code for incorrect file size
        $this->model->isTooBig = ($_FILES['image']['error'] == 1);
        $this->model->imageUploaded = true;
        $this->model->isCorrectType = $this->uploadedImage->isCorrectType();
        $this->model->isCorrect = $this->model->imageUploaded && $this->model->isCorrectType && !$this->model->isTooBig;
    }

    private function isValidUpload()
    {
        // checks if all required fields are filled
        return !empty($_POST['title']) && !empty($_FILES['image']) && !empty($_POST['watermarkText']) && !empty($_POST['author']);
    }

    private function prepareImage()
    {
        $this->uploadedImage = new uploadImage($_POST['title'], $_POST['watermarkText'], $_FILES['image'], $_POST['author']);
    }

    private function uploadImage()
    {
        if ($this->uploadedImage == null) {
            return;
        }
        $this->uploadedImage->upload();
    }
}
