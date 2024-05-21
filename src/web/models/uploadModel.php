<?php

class uploadModel
{
    public $imageUploaded;
    public $isCorrectType;
    public $isTooBig;
    public $isCorrect;

    public function __construct($imageUploaded = false, $isTooBig = false, $isCorrectType = false)
    {
        $this->imageUploaded = $imageUploaded;
        $this->isTooBig = $isTooBig;
        $this->isCorrectType = $isCorrectType;
        $this->isCorrect = $this->imageUploaded && $this->isCorrectType && !$this->isTooBig;
    }
}
