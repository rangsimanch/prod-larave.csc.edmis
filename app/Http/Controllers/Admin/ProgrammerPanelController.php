<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MassDestroyProgrammerPanelRequest;
use App\Http\Requests\StoreProgrammerPanelRequest;
use App\Http\Requests\UpdateProgrammerPanelRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProgrammerPanelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('programmer_panel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.programmerPanels.index');
    }

   public function deleteFolder(Request $request){
        $data = $request['file_ID'];
        $id_array = explode (",", $data);
        foreach($id_array as $id){
            $path = storage_path('app/public') . '/' . $id;
            $response = File::deleteDirectory($path);
        }
        // $files = scandir($path); //Get Directory
       
        return redirect()->route('admin.programmer-panels.index')->with('message', dd($response));
   }
}
