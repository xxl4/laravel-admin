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

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('emp_name', trans('admin.emp_name'));
        $grid->column('emp_name_en', trans('admin.emp_name_en'));
        $grid->column('office_code', trans('admin.office_code'));
        $grid->column('office_name', trans('admin.office_name'));
        $grid->column('company_code', trans('admin.company_code'));
        $grid->column('company_name', trans('admin.company_name'));

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

        $empModel = config('admin.database.emp_model');
        $userModel = config('admin.database.users_model');

        $form->select('user_id', trans('admin.user_id'))->options($userModel::pluck("username","id"))->rules('required');
        $form->text('emp_name', trans('admin.emp_name'))->rules('required');
        $form->text('emp_name_en', trans('admin.emp_name_en'));
        $form->text('office_code', trans('admin.office_code'));
        $form->text('office_name', trans('admin.office_name'));
        $form->select('company_code', trans('admin.company_code'))->options($empModel::selectOptions())->rules('required');
        //$form->text('company_name', trans('admin.company_name'));
        $form->text('remarks', trans('admin.remarks'));
        //$form->text('corp_code', trans('admin.corp_code'));
        //$form->text('corp_name', trans('admin.corp_name'));
        $form->hidden("company_name");
        $form->hidden("corp_code");
        $form->hidden("corp_name");

        //$form->text('name', trans('admin.name'))->rules('required');
        //$form->listbox('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        // saving the data message
        $form->saving(function($form){

        });

        return $form;
    }
}
