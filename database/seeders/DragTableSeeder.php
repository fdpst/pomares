<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DraggableList;
use App\Models\StatusTarea;

class DragTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $status = new  StatusTarea;
        $status->nombre = 'Activo';
        $status->save();



        $gD = new  DraggableList;
        $gD->user_id = 1;
        $gD->drag = 'Pendientes';
        $gD->newTask = false;
        $gD->save();

        $gD = new  DraggableList;
        $gD->user_id = 1;
        $gD->drag = 'En Progreso';
        $gD->newTask = false;
        $gD->save();

        $gD = new  DraggableList;
        $gD->user_id = 1;
        $gD->drag = 'Finalizadas';
        $gD->newTask = false;
        $gD->save();
    }
}
