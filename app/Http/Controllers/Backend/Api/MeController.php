<?php


namespace App\Http\Controllers\Backend\Api;


use App\Http\Controllers\ApiController;
use App\Transformers\Backend\UserTransformer;
use Auth;

class MeController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return $this->response()->item($user, new UserTransformer())->addMeta('is_super_admin', $user->hasRole('super_admin'));
    }
}
