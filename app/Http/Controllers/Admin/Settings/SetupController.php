<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Repositories\Settings\Repository\ISetup;
use App\Repositories\Settings\Setup;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    use UploadableTrait;

    /**
     * @var ISetup
     */
    private $setupRepo;

    public function __construct(ISetup $ISetup)
    {
        $this->setupRepo = $ISetup;
    }

    public function index()
    {
        return view('admin.settings.index', ['setup' => $this->setupRepo->findSetup()]);
    }

    public function update(Request $request,Setup $setup)
    {
        $credentials = ['send_credentials' => $request->get('send_credentials',false)];
        $overdue     = ['send_overdue' => $request->get('send_overdue',false)];
        $deadline    = ['notify_deadline' => $request->get('notify_deadline',false)];
        $data = $request->all() + $credentials + $overdue + $deadline;

        if ($request->hasFile('image')) {
            $data['url_logo'] = $this->handleUploadedImage($request->file('image'));
        }

        _addHistory(UserHistory::UPDATED,"Actualizó su cuenta");

        $setup->update($data);
        return redirect()->route('admin.settings.index')->with('success','Configuracion actualizada');
    }
}
