<?php

namespace Nicelizhi\Admin\Controllers;

use Nicelizhi\Admin\Form;
use Nicelizhi\Admin\Layout\Column;
use Nicelizhi\Admin\Layout\Content;
use Nicelizhi\Admin\Layout\Row;
use Nicelizhi\Admin\Tree;
use Nicelizhi\Admin\Widgets\Box;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Nicelizhi\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $menuModel = config('admin.database.menu_model');
                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');

                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('admin.auth.menu.edit', ['menu' => $id]);
    }

    /**
     * @return \Nicelizhi\Admin\Tree
     */
    protected function treeView()
    {
        $menuModel = config('admin.database.menu_model');

        $tree = new Tree(new $menuModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.edit'))
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = config('admin.database.menu_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $menuModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
        $form->text('title', trans('admin.title'))->rules('required');
        $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('admin.uri'));
        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        if ($form->model()->withPermission()) {
            $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
        }

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        //保存后回调
        $form->saved(function (Form $form) {
            //...插入到权限表，从而方便权限的管理与维护
            
            $model = config('admin.database.permissions_model');
            $permissionModel = config('admin.database.permissions_model');

            $http_methods = array_combine($model::$httpMethods, $model::$httpMethods);

            $http_path = $form->model()->uri."/*";
            
            foreach($http_methods as $key=>$http_method) {
                $name = Str::slug($form->model()->uri)."_".strtolower($http_method);
                $slug = Str::slug($form->model()->uri)."_".strtolower($http_method);
                $http_method_arr = [];
                $http_method_arr[] = $http_method;
                
                //check the data 
                $permission = $permissionModel::where("name", $name)->where("slug", $slug)->where("http_method", $http_method)->where("http_path", $http_path)->first();
                if(is_null($permission)) {
                    $permission = new $permissionModel();
                    $permission->name = $name;
                    $permission->slug = $slug;
                    $permission->http_path = $http_path;
                    $permission->http_method = $http_method_arr;
                    $permission->save();
                }
            }
            

        });

        return $form;
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
