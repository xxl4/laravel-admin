<?php

namespace Nicelizhi\Admin\Controllers;

use Nicelizhi\Admin\Form;
use Nicelizhi\Admin\Grid;
use Nicelizhi\Admin\Show;

class EmpUsersController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.emp-users');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $Model = config('admin.database.emp_users_model');

        $grid = new Grid(new $Model());

        $grid->column('id', 'ID')->sortable();
        $grid->column('emp_name', trans('admin.slug'));
        $grid->column('emp_name_en', trans('admin.slug'));
        $grid->column('office_code', trans('admin.name'));
        $grid->column('office_name', trans('admin.name'));
        $grid->column('company_code', trans('admin.name'));
        $grid->column('company_name', trans('admin.name'));

        //$grid->column('permissions', trans('admin.permission'))->pluck('name')->label();

        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
                $actions->disableDelete();
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $Model = config('admin.database.emp_users_model');

        $show = new Show($Model::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('admin.slug'));
        $show->field('name', trans('admin.name'));
        $show->field('permissions', trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        //$permissionModel = config('admin.database.permissions_model');
        $Model = config('admin.database.emp_users_model');

        $form = new Form(new $Model());

        $form->display('id', 'ID');

        $form->text('emp_name', trans('admin.emp_name'))->rules('required');
        $form->text('emp_name_en', trans('admin.emp_name_en'));
        $form->text('office_code', trans('admin.office_code'));
        $form->text('office_name', trans('admin.office_name'));
        $form->text('company_code', trans('admin.company_code'));
        $form->text('company_name', trans('admin.company_name'));
        $form->text('remarks', trans('admin.remarks'));
        $form->text('corp_code', trans('admin.corp_code'));
        $form->text('corp_name', trans('admin.corp_name'));

        //$form->text('name', trans('admin.name'))->rules('required');
        //$form->listbox('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
