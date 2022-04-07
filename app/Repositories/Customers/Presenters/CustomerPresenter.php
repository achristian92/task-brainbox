<?php

namespace App\Repositories\Customers\Presenters;

use App\Repositories\Tools\Presenter;
use Illuminate\Support\HtmlString;

class CustomerPresenter extends Presenter
{
    public function currentStatus()
    {
        if ($this->model->status)
            return new HtmlString("<span class='badge badge-success'>Activo</span>");
        else
            return new HtmlString("<span class='badge badge-danger'>Inactivo</span>");
    }

}
