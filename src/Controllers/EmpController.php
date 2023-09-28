<?php

namespace Nicelizhi\Admin\Controllers;

use Nicelizhi\Admin\Form;
use Nicelizhi\Admin\Layout\Column;
use Nicelizhi\Admin\Layout\Content;
use Nicelizhi\Admin\Layout\Row;
use Nicelizhi\Admin\Tree;
use Nicelizhi\Admin\Widgets\Box;
use Illuminate\Routing\Controller;

class EmpController extends Controller
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
            ->title(trans('admin.emp'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Nicelizhi\Admin\Widgets\Form();
                    $form->action(admin_url('auth/emp'));

                    $empModel = config('admin.database.emp_model');
                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');
                    $form->select('parent_id', trans('admin.parent_id'))->options($empModel::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->text('view_code', trans('admin.view_code'))->rules('required');
                    $form->text('full_name', trans('admin.full_name'))->rules('required');
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    
                    $form->hidden('order')->default(1);
                    
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
        return redirect()->route('admin.auth.emp.edit', ['menu' => $id]);
    }

    /**
     * @return \Nicelizhi\Admin\Tree
     */
    protected function treeView()
    {
        $empModel = config('admin.database.emp_model');

        $tree = new Tree(new $empModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='fa'></i>&nbsp;<strong>{$branch['title']}[{$branch['view_code']}]</strong>";

            if (!isset($branch['children'])) {
                /*
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }*/
                $uri = "";

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
            ->title(trans('admin.emp'))
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
        $empModel = config('admin.database.emp_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');


        $form = new Form(new $empModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('admin.parent_id'))->options($empModel::selectOptions());
        $form->text('title', trans('admin.title'))->rules('required');

        $form->text('view_code', trans('admin.view_code'))->rules('required');
        $form->text('full_name', trans('admin.full_name'))->rules('required');

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));

        $form->hidden('order')->default(1);

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        //
        $form->saved(function($form){

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
