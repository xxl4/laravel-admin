<?php

namespace Nicelizhi\Admin\Form\Field;

use Nicelizhi\Admin\Form\Field;

class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}
