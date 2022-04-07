<?php


namespace App\Http\Controllers\Admin\Users;


use App\Exports\CustomerExport;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserExcelController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new UserImport(), $request->file('file_upload'));
        return redirect()
            ->route('admin.users.index')
            ->with('message','Información cargada');
    }

    public function export()
    {
        $users = User::orderBy('name','asc')->get(['name','last_name','email','nro_document','password','password_plain','last_login'])->toArray();
        return Excel::download(new UserExport($users), 'LISTA-USUARIOS.xlsx');
    }

}
