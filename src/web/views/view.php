<?php
class View
{
    private $viewName;
    private $model;

    public function __construct($viewName, $model)
    {
        $this->viewName = $viewName;
        $this->model = $model;
    }

    public function render()
    {
        $model = $this->model;

        $this->renderPrefix();
        require $this->viewName . "View.php";
        $this->renderPostfix();
    }

    private function renderPrefix()
    {
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
    }

    private function renderPostfix()
    {
        echo "</html>";
    }
}
