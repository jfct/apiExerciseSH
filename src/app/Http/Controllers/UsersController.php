<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\UsersType;
use Illuminate\Http\Request;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function listTechnicians() {
        return $this->listByUserType(UsersType::getTypeId('technician'));
    }

    public function listManagers() {
        return $this->listByUserType(UsersType::getTypeId('manager'));
    }

    private function listByUserType($usersTypeId) {
        $userList = Users::where('usersTypeId', $usersTypeId)->get();
        return response()->json($userList, 200);
    }

    public function listSingle($userId) {
        return response()->json(Users::find($user));
    }

}
