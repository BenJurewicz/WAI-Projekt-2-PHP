<?php
require_once "database.php";
require_once "views/view.php";
require_once "controller.php";
require_once "classes/image.class.php";

require_once "models/galleryModel.php";

class GalleryController implements Controller
{
    public function get()
    {
        // session_start();

        $page = 0;
        if ($this->isGetPageCorrect()) {
            $page = $_GET['page'];
        }

        $images = database::getPublicImages($page, 12);
        if ($images == null && $page != 0) {
            header("Location: /?page=0");
        }


        $model = new GalleryModel($images, $page, $this->getCurrentUserName());

        return new View("gallery", $model);
    }

    private function isGetPageCorrect()
    {
        return isset($_GET["page"]) && $_GET["page"] != null && $_GET["page"] >= 0;
    }

    private function getCurrentUserName()
    {
        $user = User::getCurrentUser();
        if ($user == null || $user->username == null) {
            return null;
        }
        return $user->username;
    }
}
