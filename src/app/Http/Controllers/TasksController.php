<?php

namespace App\Http\Controllers;

use Log;
use Event;

use App\Exceptions\ValidationException;
use App\Models\Tasks;
use App\Models\Users;
use App\Models\UsersType;
use App\Events\TaskWasCreated;
use App\Jobs\SendManagerNotification;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class TasksController extends Controller {
    protected $userInfo;

    public function __construct() {
        $this->middleware('auth');
        $this->userInfo = AuthController::me();
    }

    /**
     * Lists all tasks
     */
    public function listAll() {
        if($this->userInfo->usersTypeId == UsersType::getTypeId('technician')) {
            return response()->json('Technician type users are not allowed to view all tasks.', 403);
        }
        return response()->json(Tasks::all(), 200);
    }

    /**
     * Lists all tasks by a single user
     * 
     * Managers can see all the ids
     * Technicians can only see their own tasks
     */
    public function listAllByUserId($userId) {
        try {
            if(is_null($userId) || !is_numeric($userId)) {
                return response()->json('You must provide a valid user id.', 406);
            }
    
            if ( 
                $this->userInfo->usersTypeId == UsersType::getTypeId('technician') &&
                $this->userInfo->id != $userId 
                ){
                return response()->json('You are not allowed to see this users tasks.', 403);
            }

            return response()->json(Tasks::where('userId', $userId)->get(), 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json('Failed to list tasks by user!', 500);
        }
    }

    /**
     * List a single task
     * 
     * Manager can see all the tasks
     * Technicians can only see their own tasks
     */
    public function listSingle($taskId) {
        try {
            if(is_null($taskId) || !is_numeric($taskId)) {
                return response()->json('You must provide a valid user id.', 406);
            }
    
            if ( 
                $this->userInfo->usersTypeId == UsersType::getTypeId('technician') &&
                $this->userInfo->id != $taskId 
                ){
                return response()->json('You are not allowed to see this task.', 403);
            }

            return response()->json(Tasks::find($taskId), 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json('Failed to list task!', 500);
        }
    }

    /**
     * Creates a task
     * 
     * Manager type users cannot create tasks, only the technicians
     * 
     * @param Illuminate\Http\Request
     * @return Json
     */
    public function create(Request $request) {
        $this->validate($request, [
            'date'      => 'required|Date',
            'summary'   => 'required|String|max:2500'
        ]);

        try {
            DB::beginTransaction();

            // Sanitize date
            $date       = $request->get('date');
            $cleanDate  = date('Y-m-d H:i:s', strtotime($date));
            $summary    = $request->get('summary');

            // Validations
            if(filter_var(FILTER_VALIDATE_INT, $this->userInfo->id)) {
                throw new Exception('Invalid userId');
            }
            
            // Verify if the user is a manager
            if($this->userInfo->usersTypeId == UsersType::getTypeId('manager')) {
                return response()->json('Manager type users are not allowed to make tasks.', 403);
            }

            // Check if the users exists in the DB
            if (Users::find($this->userInfo->id)) {
                $task = Tasks::create(array(
                    'date'      => $cleanDate,
                    'summary'   => Crypt::encrypt($summary),
                    'userId'    => $this->userInfo->id,
                    'createdAt' => date('Y-m-d H:i:s')
                ));
            } else {
                return response()->json('User not found!', 406);
            }

            DB::commit();

            // Fire the event to the listener
            Event::dispatch(new TaskWasCreated($task));

            return response()->json($task, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json('Failed to create task!', 500);
        }
    }

    public function update($taskId, Request $request) {
        $this->validate($request, [
            'id'        => 'prohibited',
            'userId'    => 'prohibited'
        ]);

        try {
            
            $task = Tasks::find($taskId);

            // Verify if the user is a manager or himself
            if($this->userInfo->usersTypeId == UsersType::getTypeId('technician') && $this->userInfo->id != $task->userId) {
                return response()->json('You are not allowed to update this task.', 403);
            }

            if (!is_null($task->id)) {
                $params                 = $request->all();
                $params['updated_at']   = date('Y-m-d H:i:s');
                $task->update($params);
            } else {
                return response('Task not found', 201);
            }
    
            return response()->json($task, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json('Failed to update task!', 500);
        }
    }

    public function delete($taskId) {
        try {
            Tasks::findOrFail($taskId)->delete();
            return response('Deleted successfully', 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json('Failed to delete task!', 500);
        }

    }

}
