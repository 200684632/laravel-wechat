<?php

namespace App\Http\Controllers;

use App\WechatQrCode;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        return '首页';
    }

}
