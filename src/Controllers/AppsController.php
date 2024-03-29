<?php

namespace Nicelizhi\Admin\Controllers;

use Nicelizhi\Admin\Form;
use Nicelizhi\Admin\Grid;
use Nicelizhi\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use \GuzzleHttp\Psr7;
use \GuzzleHttp\Exception\ClientException;
use Nicelizhi\Admin\Layout\Content;

class AppsController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.apps');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $items = $this->SyncApps();

        $Model = config('admin.database.apps_model');

        $grid = new Grid(new $Model());

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', trans('admin.username'));
        $grid->column('name', trans('admin.name'));
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->disableCreation();

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
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('admin.username'));
        $show->field('name', trans('admin.name'));
        $show->field('roles', trans('admin.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
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
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');
        $empModel = config('admin.database.emp_model');

        $form = new Form(new $userModel());

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));
        $form->multipleSelect('emp', trans('admin.emp'))->options($empModel::all()->pluck('title', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }

    /**
     * 
     * 同步Apps 资源内容
     * 
     */
    private function SyncApps() {
        $cache_key = "apps_data";
        $body = Cache::get($cache_key);
        if($body==false) {
            $api_url = config("admin.apps_url");
            if(empty($api_url)) throw new \Exception("admin apps url is not config,pls check your admin php file");
            $url = $api_url."resource.json";
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $url, [
                    'auth' => ['user', 'pass']
                ]);
    
                $body = json_decode($res->getBody());
    
                if($body->status==200) {
                    Cache::set("apps_data", $body, 3600);
                }
    
                return $body;
                
            }catch(ClientException $e) {
                echo Psr7\Message::toString($e->getRequest());
                echo Psr7\Message::toString($e->getResponse());
                
            } 
        }
    }

    public function news(Content $content) {
        return $content->view("admin::apps.news");
    }

    /**
     * 
     * 用户登录页面
     * 
     */
    public function login(Content $content, Request $request) {
        


        return $content->view("admin::apps.login");
    }
}
