<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Author;
use App\Models\Comment;
use App\Models\Rating;
use App\Http\Models\AdminUser;

class UserController extends Controller
{
    public function view(AdminUser $user )
    {
        return view('user', ['user' => $user]);
    }
}
