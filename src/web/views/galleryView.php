<?php
require_once "classes/user.class.php";

function displayPageInterface($page)
{
    echo
    "<div class='pageButtonWrapper'>" .
        "<button class='lastNextButtons' onclick='changePage(-1)'>Last</button>" .
        "<p class='pageNumber'>Page:" . $page . "</p>" .
        "<button class='lastNextButtons' onclick='changePage(1)'>Next</button>" .
        "</div>";
}

function displayImageCard($image)
{
    echo
    "<div class='galleryCard'>
        <a href='$image->watermarkPath'>        
        <img src='$image->thumbnailPath' />
        </a>
                <h3> $image->title </h3>
                <h4> $image->author </h4>
    </div>";
}

function displayUsername($model)
{
    if ($model->username != null) {
        echo "<p>Logged in as: " . $model->username . "</p>";
    }
}

?>

<head>
    <?php require 'components/head.php'; ?>
    <script src="scripts/gallery.js"></script>
</head>

<body>
    <h1 class="title">Galeria</h1>
    <?php require "components/nav.php"; ?>
    <?php displayPageInterface($model->page); ?>

    <div class="gallery">
        <?php
        if (!empty($model->images)) {
            foreach ($model->images as $image) {
                displayImageCard($image);
            }
        }
        ?>
    </div>

    <?php
    displayPageInterface($model->page);
    displayUsername($model);
    ?>
</body>