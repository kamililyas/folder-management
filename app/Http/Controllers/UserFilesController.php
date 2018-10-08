<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFileRequest;
use App\Services\Files\CreateFileService;
use App\User;
use App\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            return $this->setStatusCode(200)->responseSuccess([
                'message' => trans('messages.user_files_list_success'),
                'user_files' => Auth::user()->files()->where('parent_id', (isset($request->parent_id) && $request->parent_id && !is_null($request->parent_id) && $request->parent_id != 'null') ? $request->parent_id : null)->get(),
            ]);
        } catch (\Exception $exception) {

            return $this->responseException($exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFileRequest $request)
    {
        try {
            # Store a file against the user
            $createService = new CreateFileService($request->user(), $request->input(), $request->file());
            $files = $createService->create();

            return $this->setStatusCode(200)->responseSuccess([
                'message' => trans('messages.user_files_stored_success'),
                'files' => $files,
            ]);
        } catch (\Exception $exception) {
            return $this->responseException($exception);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserFile  $userFiles
     * @return \Illuminate\Http\Response
     */
    public function show(UserFile $userFiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserFile  $userFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(UserFile $userFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserFile $userFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFile $userFiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserFile  $userFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFile $userFiles)
    {
        //
    }
}
