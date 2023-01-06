<?php

namespace Nicelizhi\Admin\Widgets\Navbar;

use Nicelizhi\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Admin::component('admin::components.refresh-btn');
    }
}
