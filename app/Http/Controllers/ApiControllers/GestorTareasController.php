<?php

namespace App\Http\Controllers\ApiControllers;

use Auth;
use stdClass;
use Carbon\Carbon;
use App\Models\User;
use App\Models\GestorTarea;
use App\Models\StatusTarea;
use Illuminate\Http\Request;
use App\Models\DraggableList;

use App\Models\TareaPorDragg;

use App\Models\TareaPorUsuario;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GestorTareasController extends Controller
{
   


    public function newDrag(Request $request, $user_id){
       
        $user_id =  $user_id;
        
        $drag = null;
        
        try {
            $drag = new DraggableList;
            $drag->drag = ucfirst($request->drag);
            $drag->user_id = $user_id;
            $drag->newTask = false;
            $drag->saveOrFail();

        
            $users = User::where('role', 1)->get(['id']);
            foreach ($users as $key => $user) {
                $dragAll = new DraggableList;
                $dragAll->drag = ucfirst($request->drag);
                $dragAll->user_id = $user['id'];
                $dragAll->newTask = false;
                $dragAll->saveOrFail();
            }
        } catch (Exception $e) {
            
        }
        



        $drag['list'] = Array();
        $drag['editModeDrag'] = false;
        return response()->json([
            'status' => 200,
            'drag' => $drag
        ]);
    }
   


    


    public function updateDrag(Request $request, $id){

       
            $drag = DraggableList::findOrFail($id);
            $drag->drag = $request['drag'];
            $drag->update();

            return response()->json([
                'status' => 200,
                'drag' => $drag
            ]);
     }

      public function deleteDrag($id){

            $drag = DraggableList::where('id', $id)->first();

            $drags = DraggableList::where('drag', $drag->drag)->get();


            return  $drags;
            $drag = DraggableList::findOrFail($id)->delete();



            return response()->json([
                'status' => 200,
                'drag' => $drag
            ]);
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Save task
        $taskRequest = json_decode($request->task);
        $draggable = json_decode($request->draggable);
        $administradores = json_decode($request->administradores);
        $nroAdmin = count($administradores);

        $user_id = $request->user_id;

        $task = null;
        
       
       
        if ($request->isMethod('post')) {

            try {
               
                $task = new GestorTarea;
                $task->status_id = 1;
                $task->titulo = ucfirst($taskRequest->titulo);
                $task->descripcion = $taskRequest->descripcion;
                $task->fecha_creacion = Carbon::now();
                $task->saveOrFail();

                $newTaskUser = new TareaPorUsuario;
                $newTaskUser->user_id = $user_id;
                $newTaskUser->tarea_id = $task->id;
                $newTaskUser->saveOrFail();

                $newTasDrag = new TareaPorDragg;
                $newTasDrag->drag_id = $draggable->id;
                $newTasDrag->tarea_id = $task->id;
                $newTasDrag->status = $draggable->drag;
                $newTasDrag->saveOrFail();

                if ($nroAdmin>0) {
                    
                    $drags = [];
                    foreach ($administradores as  $admin) {
                        $newTaskUser = new TareaPorUsuario;
                        $newTaskUser->user_id = $admin->id;
                        $newTaskUser->tarea_id = $task->id;
                        $newTaskUser->saveOrFail();

                        $drags[] = DraggableList::where(['user_id' => $admin->id, 'drag' => $draggable->drag])->get(['id']);

                    }

                    $dragsNew=[];
                    foreach ($drags as $key => $array) {
                        foreach ($array as $keys => $value) {
                           $dragsNew[count($dragsNew)]= $value;
                        }
                    }

                    foreach ($dragsNew as  $drag) {
                        $newTasDrag = new TareaPorDragg;
                        $newTasDrag->drag_id = $drag->id;
                        $newTasDrag->tarea_id = $task->id;
                        $newTasDrag->status = $draggable->drag;
                        $newTasDrag->saveOrFail();
                    }
                }
                


            } catch (Exception $e) {
                
            }


           
        }else{
             return response()->json([
                'status' => 500,
                'message' => 'The Method is invalid'
            ]);
        }
        

         return response()->json([
            'status' => 200,
            'task' => $task
        ]);
    }


    public function index($user_id)
    {

        $user_id = $user_id;

       

        $dragglables = DraggableList::where('user_id', $user_id)->get(['id', 'user_id', 'drag', 'newTask']);

        $dragsTasksGet = [];
        foreach ($dragglables as $key => $value) {
            $dragsTasksGet [] = TareaPorDragg::with('drag', 'task')->where('drag_id', $value->id)->get();
        }
        $dragsTasks = [];
        foreach ($dragsTasksGet as $key => $array) {
            foreach ($array as $keys => $value) {
               $dragsTasks[count($dragsTasks)]= $value;
            }
        }

        $status = StatusTarea::get(['id', 'nombre']);
        $tasksForUser = TareaPorUsuario::with('task', 'user')->where('user_id', $user_id)->orderBy('id','desc')->get(['user_id' , 'tarea_id']);
        
        $obtainTasks = [];


        $tasks = [];
        $administradores = [];
        foreach ($tasksForUser as $taskUser) {
            $tasks[] = $taskUser->task;
            foreach ($tasks as $property) {
                $property['user'] = $taskUser->user;
                $property['status'] = StatusTarea::where('id', $property->status_id)->first(['id', 'nombre']);
            }
        }
        
        foreach ($dragglables as $key => $value) {
            $value['list'] = Array();
            $value['editModeDrag'] = false;
        }

        $users = User::where('role', 1)->get(['id', 'name' ,'role', 'email']);


        $tareasAdmin  = TareaPorUsuario::get(['tarea_id', 'user_id']);
       


        return response()->json([
            'status' => 200,
            'tasks' => $tasks,
            'dragglables' => $dragglables,
            'dragsTasks' => $dragsTasks,
            'users' => $users,
            'tareasAdmin' => $tareasAdmin
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {

        
        $newTaskInDrag = $request->task;
        $drag =  $request->hasta;
        
        $listUsers = $newTaskInDrag['listAdmins'];

        $drags = [];
        foreach ($listUsers as $key => $user_id) {
           $drags[] = DraggableList::where(['user_id' => $user_id['user_id'], 'drag' => $drag])->first();
        }

        if ($request->isMethod("put")) {
            try {
                
                
                $obtenerTareas = TareaPorDragg::where('tarea_id', $newTaskInDrag['id'])->get();
                
                $x = 0;
                foreach ($drags as  $dragOne) {

                    $dragTareas = TareaPorDragg::findOrFail($obtenerTareas[$x]['id']);
                    $dragTareas->drag_id = $dragOne['id'];
                    $dragTareas->status =  $drag;
                    $dragTareas->update();
                    $x = $x+1;
                }

                return response()->json([
                    'status' => 200,
                    'task' => $newTaskInDrag
                ]);
            } catch (Exception $e) {
                
            } 
        }else{
            return response()->json([
                'message' => 'Method Ivalid',
               
            ]);
        }
        
    }



    public function update(Request $request, $id){


        $taskUp = $request->task;
        $drag = $request->draggable;
        $user_id = $request->user_id*1;
        $administradores = $request->administradores;

        
        $validaUser = User::where('id', $user_id)->first('role');

        if ($validaUser->role == 1) {
            return response()->json([
                'status' => 100
            ]);
        }

        $users = User::where('role',1)->get();
        $kills = TareaPorUsuario::where('tarea_id', $taskUp['id'])->get();
        $obtenerUsuariosDeTarea = [];
        $obtenerDrags= [];
        
        $task  = null;
        if ($request->isMethod('put')) {
            
                $task = GestorTarea::findOrFail($taskUp['id']);
                $task->status_id = 1;
                $task->titulo = ucfirst($taskUp['titulo']);
                $task->descripcion = $taskUp['descripcion'];
                // $task->fecha_creacion = Carbon::now();
                $task->update();
                

                if (count($administradores)>0) {
                    if (count($kills) > 0) {
                        foreach ($users as $user) {
                            foreach ($kills as $tareaUser) {
                                if ($user['id'] ==  $tareaUser['user_id']) {
                                     $obtenerUsuariosDeTarea[] = $user['id'];
                                }
                            }
                        }

                        foreach ($kills as  $tarea) {
                            $obtenerDrags[] = TareaPorDragg::where('tarea_id', $tarea['tarea_id'])->get();
                        }
                        foreach ($obtenerUsuariosDeTarea as $idUser) {
                            foreach ($kills as $tareaU) {
                                if ($tareaU['user_id'] == $idUser) {
                                    TareaPorDragg::where('tarea_id', $tareaU['tarea_id'])->delete();
                                    TareaPorUsuario::findOrFail($tareaU['id'])->where('user_id', $idUser)->delete();
                                   
                                }
                            }
                            
                        }

                        $newTasDrag = new TareaPorDragg;
                        $newTasDrag->drag_id = $drag['id'];
                        $newTasDrag->tarea_id = $taskUp['id'];
                        $newTasDrag->status = $drag['drag'];
                        $newTasDrag->saveOrFail();


                        $drgt = [];
                        foreach ($obtenerDrags as $key => $array) {
                            foreach ($array as $keys => $value) {
                                $drgt[count($drgt)]= $value;
                            }
                        }

                        
                        foreach ($administradores as $value) {
                            $tareaPorUsuario = new TareaPorUsuario;
                            $tareaPorUsuario->tarea_id = $taskUp['id'];
                            $tareaPorUsuario->user_id = $value['id'];
                            $tareaPorUsuario->save();

                            $drags[] = DraggableList::where(['user_id' => $value['id'], 'drag' => $drag['drag']])->get();
                        }


                        $dragsNew=[];
                        foreach ($drags as $key => $array) {
                            foreach ($array as $keys => $value) {
                               $dragsNew[count($dragsNew)]= $value;
                            }
                        }
                       
                        foreach ($dragsNew as  $drag) {
                            $newTasDrag = new TareaPorDragg;
                            $newTasDrag->drag_id = $drag->id;
                            $newTasDrag->tarea_id = $task->id;
                            $newTasDrag->status = $drag->drag;
                            $newTasDrag->saveOrFail();
                        }
                    }else{
                        foreach ($administradores as $value) {
                            $tareaPorUsuario = new TareaPorUsuario;
                            $tareaPorUsuario->tarea_id = $taskUp['id'];
                            $tareaPorUsuario->user_id = $value['id'];
                            $tareaPorUsuario->save();

                            $drags[] = DraggableList::where(['user_id' => $value['id'], 'drag' => $drag['drag']])->get();
                        }

                        $dragsNew=[];
                        foreach ($drags as $key => $array) {
                            foreach ($array as $keys => $value) {
                               $dragsNew[count($dragsNew)]= $value;
                            }
                        }
                       
                        foreach ($dragsNew as  $drag) {
                            $newTasDrag = new TareaPorDragg;
                            $newTasDrag->drag_id = $drag->id;
                            $newTasDrag->tarea_id = $task->id;
                            $newTasDrag->status = $drag->drag;
                            $newTasDrag->saveOrFail();
                        }
                    }
                    
                }
           


           
        }else{
             return response()->json([
                'status' => 500,
                'message' => 'The Method is invalid'
            ]);
        }
        

         return response()->json([
            'status' => 200,
            'task' => $task
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $task = GestorTarea::findOrFail($id)->delete();

        return response()->json([
            'status' => 200,
            'task' => $task
        ]);
    }


}
