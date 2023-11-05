<?php

namespace App\Admin\Controllers;

use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Http\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Encore\Admin\Controllers\UserController;

class CustomUserController extends UserController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.administrator');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminUser());
        $grid->column('id', __('Id'));
        $grid->column('username', __('Username'));
        $grid->column('name', __('Name'));
        $grid->column('avatar', __('Avatar'))->image();
        $grid->column('slug', __('slug'));
        $grid->column('job', __('Job'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('title', __('Title'));
        $grid->column('twitter', __('Twitter'));
        $grid->column('facebook', __('Facebook'));
        $grid->column('instagram', __('Instagram'));
        $grid->column('skype', __('Skype'));
        $grid->column('linkedin', __('Linkedin'));
        $grid->column('birth_day', __('Birth day'));
        $grid->column('email', __('Email'));
        $grid->column('address', __('Address'));
        $grid->column('slogan', __('Slogan'));
        $grid->column('image', __('Image'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });
        $grid->filter(function($filter){
            $filter->where(function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });   
            }, 'Role');
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
        $show = new Show(AdminUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('username', __('Username'));
        $show->field('password', __('Password'));
        $show->field('name', __('Name'));
        $show->field('avatar', __('Avatar'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('job', __('Job'));
        $show->field('phone_number', __('Phone number'));
        $show->field('title', __('Titile'));
        $show->field('twitter', __('Twitter'));
        $show->field('facebook', __('Facebook'));
        $show->field('instagram', __('Instagram'));
        $show->field('skype', __('Skype'));
        $show->field('linkedin', __('Linkedin'));
        $show->field('birth_day', __('Birth day'));
        $show->field('email', __('Email'));
        $show->field('address', __('Address'));
        $show->field('slogan', __('Slogan'));
        $show->field('image', __('Image'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new AdminUser());

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
        //$form->select('branch_id', "Chi nhánh")->options(Branch::all()->pluck('branch_name', 'id'))->default(3);
        //$form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));
        $form->text('job', __('Job'));
        $form->text('phone_number', __('Phone number'));
        $form->text('title', __('Title'));
        $form->url('twitter', __('Twitter'));
        $form->url('facebook', __('Facebook'));
        $form->url('instagram', __('Instagram'));
        $form->text('skype', __('Skype'));
        $form->url('linkedin', __('Linkedin'));
        $form->date('birth_day', __('Birth day'))->default(date('Y-m-d'));
        $form->email('email', __('Email'));
        $form->text('address', __('Address'));
        $form->textarea('introduction', __('Introduction'));
        $form->text('slogan', __('Slogan'));
        $form->image('image', __('Image'));
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}