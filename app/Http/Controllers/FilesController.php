<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\StoreFileRequest;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $files = File::all();
        // return view('files.index', [
        //     'files' => $files
        // ]);

        $departments = Department::all();
        $users = User::all();
        $files = File::latest()->paginate(10);
        return view('files.index', compact('files','departments','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['departments']=Department::select('id','name')->orderBy('name')->get();
        return view('files.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request) 
    {
        // $fileName = auth()->id() . '_' . time() . '.'. $request->file->extension();  
        $doc_name = $request->input('doc_name') . '_' . time() . '.'. $request->file->extension();  
        $doc_date = $request->input('doc_date');  
        $doc_date_exp = $request->input('doc_date_exp');  
        $doc_note = $request->input('doc_note');  
        $dept_id = $request->input('dept_id');  

        $doc_type = $request->file->getClientMimeType();
        $doc_size = $request->file->getSize();

        $request->file->move(public_path('file'), $doc_name);

        File::create([
            'doc_name' => $doc_name,
            'doc_date' => $doc_date,
            'doc_date_exp' => $doc_date_exp,
            'doc_note' => $doc_note,
            'doc_type' => $doc_type,
            'doc_size' => $doc_size,
            'dept_id' => $dept_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('files.index')->withSuccess(__('File added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('files.create')
            ->withSuccess(__('Upload File only.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('files.create')
            ->withSuccess(__('Upload File only.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        // $file->update($request->only('doc_name', 'doc_date', 'doc_date_exp', 'doc_note', 'doc_size', 'dept_id', 'updated_at'));
        // return redirect()->route('files.index')
        //     ->withSuccess(__('File updated successfully.'));
        return redirect()->route('files.create')
            ->withSuccess(__('Upload File only.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    // public function destroy(File $file)
    {
        // DB::enableQueryLog();

        // $file->delete();
        // return redirect()->route('files.index')
        //     ->withSuccess(__('File deleted successfully.'));

        // dd($file->delete());

        $file = File::find($id);
        $file->delete();
        return redirect()->route('files.index')
            ->withSuccess(__('File deleted successfully.'));

    }
}
