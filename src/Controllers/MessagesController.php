<?php

namespace Nicelizhi\Admin\Controllers;

use Nicelizhi\Admin\Auth\Database\Message;
use Nicelizhi\Admin\Grid;
use Illuminate\Support\Arr;
use Nicelizhi\Admin\Show;

class MessagesController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.messages');
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Message());


        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('sender.name', 'User');
        $grid->column('receiver.name', 'User');
        $grid->column('title')->label('info');
        $grid->column('created_at', trans('admin.created_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $userModel = config('admin.database.users_model');
            $filter->equal('receiver_id', 'User')->select($userModel::all()->pluck('name', 'id'));
            $filter->like('title');
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
        $Model = config('admin.database.messages_model');

        $show = new Show($Model::findOrFail($id));

        //$show->field('id', 'ID');
        $show->field('title', 'Title');
        $show->field('message', 'Message');
       
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        //todo update message read status and read time

        return $show;
    }

    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        if (OperationLog::destroy(array_filter($ids))) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }
}
