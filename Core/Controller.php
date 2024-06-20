<?php
class Controller
{
    public function model($model)
    {
        include_once './Models/'.$model.'.php';
        return new $model;
    }
}