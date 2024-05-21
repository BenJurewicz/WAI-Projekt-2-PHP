<?php
function echoInP($text)
{
    echo "<p class='margin0'>$text</p>";
}
?>

<head>
    <?php require 'components/head.php'; ?>
</head>

<body>
    <h1 class="title">Upload Image</h1>
    <?php require "components/nav.php"; ?>
    <form class="basicForm" action="upload" method="post" enctype="multipart/form-data">
        <label for="title">Title:
            <input type="text" name="title" required />
        </label>
        <label for="title">Watermark text:
            <input type="text" name="watermarkText" required />
        </label>
        <label for="author">Author:
            <input type="text" name="author" required />
        </label>
        <label for="image">Image file:
            <input type="file" name="image" required />
        </label>
        <input type="submit" value="Submit" />
    </form>
    <div class="flexColumn errorList">

        <?php
        if ($model->imageUploaded) {
            if ($model->isTooBig) {
                echoInP("File is too big. Maximum file size is 1MB.");
            } else if (!$model->isCorrectType) {
                echoInP("Incorrect file type. Correct file types are only \".png\" and \".jpg\".");
            }

            if ($model->isCorrect) {
                echoInP("File uploaded successfully.");
            }
        }
        ?>
    </div>
</body>