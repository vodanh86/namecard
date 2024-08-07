<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Http\Models\Token;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TokenController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Token';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Token());

        $grid->column('id', __('Id'));
        $grid->column('token', __('Token'))->width(800);
        $grid->column('expired_date', __('Expired date'));
        $grid->column('time_stamp', __('TimeStamp'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Token::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('token', __('Token'));
        $show->field('expired_date', __('Expired date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Token());

        $form->textarea('token', __('Token'));
        $form->text('expired_date', __('Expired date'));
        $form->text('time_stamp', __('Time Stamp'));
        // callback before save
        $form->saving(function (Form $form) {
            $params = explode("steamLoginSecure=", $form->token);
            if (count($params) > 1) {
                $params = explode("'", $params[1]);
                list($header, $payload, $signature) = explode('.', $params[0]);
                $jsonToken = base64_decode($payload);
                $arrayToken = json_decode($jsonToken, true);
                $form->time_stamp = $arrayToken["exp"];
                $form->expired_date = Carbon::createFromTimestamp($arrayToken["exp"])->toDateTimeString();
            }
        });
        return $form;
    }
}
