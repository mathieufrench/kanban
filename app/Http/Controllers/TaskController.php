<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;
use Redirect;

class TaskController extends Controller
{
    public function index()
    {

        $tasks = Task::all();

        $grouped = $tasks->mapToGroups(function ($task, $key) {
            return [$task['status_id'] => collect($task)];
        });

        $statuses = Status::all();

        return view('tasks.index')->with('tasks', $grouped)->with('statuses',$statuses);
    }




    public function create(Request $request){

        // dd($request->title);
        $task = new Task();
        $task->title = $request->title;
        $task->status_id = $request->status_id;
        $task->save();

        return Redirect::back()->with('message','New task created!');
    }

    public function update(Request $request){

        $task = Task::where(['id'=>$request->taskId])->update([
            'status_id' => $request->statusId
        ]);

        return("success");
    }

    public function delete(Request $request){
        Task::where(['id'=>$request->taskId])->delete();
        return Redirect::back()->with('message','Task Deleted');
    }

}
