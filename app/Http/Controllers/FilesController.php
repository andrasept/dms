<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Department;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Log;

use Illuminate\Database\Query\Builder;

use Illuminate\Validation\Validator;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    public function index(Request $request)
    {
        // $files = File::all();
        // return view('files.index', [
        //     'files' => $files
        // ]);

        $departments = Department::all();
        $users = User::all();
        // $files = File::latest()->paginate(10);
        // $files = File::latest()->paginate(10);
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;
        // category_id cari dari table user dan category
        // select category_id where category.created_by = user.id
        // select categories.id, categories.name from files, users, categories where categories.created_by = users.id AND files.category_id=categories.id
        
        // $files_cats = DB::table('files')
        //     ->join('categories', 'files.category_id', '=', 'categories.id')
        //     ->join('users', 'categories.created_by', '=', 'users.id')
        //     ->select('categories.id', 'categories.name')
        //     ->get();
        // var_dump($files_cats); exit();

        // files filter
        // $category_id = $request->input('category_id');
        // if($category_id!=""){
        //     $files_filter = File::where(function ($query) use ($category_id){
        //         $query->where('category_id', $category_id);
        //     })
        //     ->paginate(10);
        //     $files_filter->appends(['category_id' => $category_id]);
        //     // echo "1"; exit();
        // }
        // else{
        //     $files_filter = File::paginate(10);
        //     // echo "2"; exit();
        // }
        // files filter end


        if ($dept_id == 0) { // jika admin Login
            $user_id = auth()->user()->id;
            $dept_id = auth()->user()->dept_id;
            // $files = File::latest()->paginate(10);
            $categories = Category::all();
            // files filter
            $category_id = $request->input('category_id');
            if($category_id!=""){
                $files = File::where(function ($query) use ($category_id){
                    $query->where('category_id', $category_id);
                })
                ->paginate(10);
                $files->appends(['category_id' => $category_id]);

                // expired docs
                $files_exp = File::where(function ($query) use ($category_id){
                    $query->where('category_id', $category_id);
                })->get();
            }
            else{
                $files = File::paginate(10);
                // expired docs
                $files_exp = File::all();
            }
            // files filter end
        } else {
            $user_id = auth()->user()->id;
            $dept_id = auth()->user()->dept_id;
            // $files = File::where('created_by',$user_id)->orWhere('dept_id',$dept_id)->paginate(10);
            // $categories = Category::where('created_by',$user_id)->orWhere('category_id',$category_id)->get();
            $categories = DB::table('files')
                ->join('categories', 'files.category_id', '=', 'categories.id')
                ->join('users', 'categories.created_by', '=', 'users.id')
                ->select('categories.id', 'categories.name')
                ->get();
            // select `categories`.`id`, `categories`.`name` from `files`, `categories`, `users` where `files`.`category_id` = `categories`.`id` AND `categories`.`created_by` = `users`.`id` GROUP by categories.id 
            // $categories = DB::select()

            // files filter
            $category_id = $request->input('category_id');
            if($category_id!=""){
                // DB::enableQueryLog();

                // if (condition) {
                //     // code...
                // }
                // select cat_id, where created_by = auth->user_id AND (dept_id = auth->dept_id OR dept_id) AND category_id = cat_id
                
                // query tambahkan "AND" untuk dept_id = 15 (all department)
                // $files = File::where(function ($query) use ($category_id){
                //     // $query->where('created_by',auth()->user()->id)->orWhere('dept_id',auth()->user()->dept_id)
                //     $query
                //         // ->where('dept_id',auth()->user()->dept_id)
                //         // ->orWhere('dept_id', 15)
                //         ->where('created_by', auth()->user()->id)
                //         // ->orWhere('created_by', 4)
                //         ->where('category_id', $category_id);
                // })
                // ->paginate(10);
                // $files->appends(['category_id' => $category_id]);

                // $files = DB::select( DB::raw("
                //         SELECT * FROM files, departments, users WHERE (files.created_by = '$user_id' OR files.created_by = 4) AND (files.dept_id = '$dept_id' OR files.dept_id = 15) AND files.created_by = users.id AND files.dept_id = departments.id AND files.category_id = '$category_id' 
                //         "));

                // $files_query = DB::select( DB::raw("
                //         SELECT * FROM files, departments, users WHERE (files.created_by = '$user_id' OR files.created_by = 4) AND (files.dept_id = '$dept_id' OR files.dept_id = 15) AND files.created_by = users.id AND files.dept_id = departments.id AND files.category_id = '$category_id' 
                //         "));
                // $files = $files_query->paginate(10);
                // $files->appends(['category_id' => $category_id]);

                // $files = DB::table('files')
                // ->selectRaw('files.*')
                $files = File::selectRaw('files.*')
                ->join('users', 'files.created_by', 'users.id')
                ->join('departments', 'files.dept_id', 'departments.id')
                ->whereRaw('(files.created_by = '.$user_id.' OR files.created_by = 4) AND (files.dept_id = '.$dept_id.' OR files.dept_id = 15) AND files.category_id = '.$category_id)
                ->paginate(10);
                $files->appends(['category_id' => $category_id]);

                // expired docs
                // $files_exp = DB::table('files')
                // ->selectRaw('files.*')
                $files_exp = File::selectRaw('files.*')
                ->join('users', 'files.created_by', 'users.id')
                ->join('departments', 'files.dept_id', 'departments.id')
                ->whereRaw('(files.created_by = '.$user_id.' OR files.created_by = 4) AND (files.dept_id = '.$dept_id.' OR files.dept_id = 15) AND files.category_id = '.$category_id)
                ->get();

                // $query = DB::getQueryLog();
                // dd($query); exit();
            }
            else{
                $files = File::where('created_by',$user_id)->orWhere('dept_id',$dept_id)->paginate(10);
                // expired docs
                $files_exp = File::where('created_by',$user_id)->orWhere('dept_id',$dept_id)->get();
            }
            // files filter end

        }        
        // $categories = Category::all();
        // $parentCategories = Category::where('parent_id',0)->get();
        if ($dept_id == 0) { // Admin
            $parentCategories = Category::where('parent_id',0)->get();
        } else {
            // $parentCategories = Category::where('parent_id',0)->where('created_by',$user_id)->latest()->paginate(10);
            $parentCategories = Category::where('parent_id',0)->where('created_by',$user_id)->get();
        }
        
        $current_date = date('Y-m-d');    
        // $current_date = "2012-12-07";    

        $check_date_exp = 0;
        $i = 0;
        foreach ($files_exp as $f) {  
            // if ($f->doc_date_exp || $f->doc_date) {
            if ($f->doc_date_exp) {
                // echo $date_exp->doc_date_exp."<br/>";
                $date_exp = $f->doc_date_exp;
                $orderdate=$date_exp;
                $orderdate = explode('-', $orderdate);
                $day   = $orderdate[2];
                $month = $orderdate[1];
                $year  = $orderdate[0];

                $date_exp = Carbon::create($year, $month, $day, 0);
                // expire date
                // echo $date_exp."<br/>"; 
                // 1 month to expired
                $date_exp_2mo = $date_exp->subMonth(2);
                // echo $date_exp_2mo."<br/>"; 
                // if curr_date >= 2-mo-to-exp && curr_date <= date_exp
                // if (($current_date >= $date_exp_2mo) && ($current_date <= $date_exp)) {
                if (($current_date >= $date_exp_2mo) || ($current_date >= $date_exp)) {
                    $i++;
                    // echo $f->doc_name;
                    // echo " masuk ke masa tenggang expired di ".$date_exp."<br/><br/>";
                    $check_date_exp=1;
                    // echo $i."<br/>";
                }
            }            

            // LANJUT MASUKKAN NOTIF NYA (ATAU LOGIC NYA) KE BLADE
        }
        // echo $i;

        if ($i>0) {
            $total_date_exp = $i; 
        } else {
            $total_date_exp = 0;
        }
        // echo $total_date_exp; exit();

        $tests_navbar = 2;
        $tests_navbar = "test";
        
        return view('files.index', compact('files','departments','users','current_date','check_date_exp','total_date_exp', 'parentCategories','tests_navbar','categories','files_exp'));
        // return view('files.index', compact('files','departments','users','doc_date_exps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments=Department::select('id','name')->orderBy('name')->get();

        $users = User::all();
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;

        if ($dept_id == 0) { // jika admin Login
            $files = File::all();
            $categories = Category::all();
        } else {
            $files = File::where('created_by',$user_id)->orWhere('dept_id',$dept_id)->paginate(10);
            // $categories = Category::where('created_by',$user_id)->orWhere('category_id',$category_id)->get();
            $categories = DB::table('files')
                ->join('categories', 'files.category_id', '=', 'categories.id')
                ->join('users', 'categories.created_by', '=', 'users.id')
                ->select('categories.id', 'categories.name')
                ->get();
        }        
        // $categories = Category::all();
        // $parentCategories = Category::where('parent_id',0)->get();
        if ($dept_id == 0) { // Admin
            $parentCategories = Category::where('parent_id',0)->get();
        } else {
            $parentCategories = Category::where('parent_id',0)->where('created_by',$user_id)->latest()->paginate(10);
        }

        // return view('files.create', $data);
        return view('files.create', compact('files','departments','users','parentCategories','categories'));
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
        $dept_id = $request->input('dept_id');
        $category_id = $request->input('category_id');

        // $doc_name = $request->input('doc_name') . '_' . time() . '.'. $request->file->extension();  
        $doc_name = $request->input('doc_name') . '.' . $request->file->extension();  
        $doc_number = $request->input('doc_number');  
        $doc_date = $request->input('doc_date');  
        $doc_date_exp = $request->input('doc_date_exp');  
        $doc_note = $request->input('doc_note');  

        // $validatedData = $request->validate([
        //  'file' => 'required|csv,txt,xlx,xls,xlsx,doc,docx,pdf,ppt,pptx,tif',
        // ]);
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,ppt,pptx,tif|max:20480'
        ]);

        $doc_type = $request->file->getClientMimeType();
        $doc_size = $request->file->getSize();

        $request->file->move(public_path('file'), $doc_name);

        File::create([
            'dept_id' => $dept_id,
            'category_id' => $category_id,
            'doc_number' => $doc_number,
            'doc_name' => $doc_name,
            'doc_date' => $doc_date,
            'doc_date_exp' => $doc_date_exp,
            'doc_note' => $doc_note,
            'doc_type' => $doc_type,
            'doc_size' => $doc_size,
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
    // public function destroy($id)
    public function destroy(Request $request, $id)
    // public function destroy(File $file)
    {
        // echo $id; exit();

        // get deleted by and insert into log history
        // code...
        Log::create([
            'user_id' => auth()->user()->id,
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
            'last_delete_file_at' => Carbon::now()->toDateTimeString(),
            'last_delete_file_id' => $id,
        ]);

        // DB::enableQueryLog();

        // $file->delete();
        // return redirect()->route('files.index')
        //     ->withSuccess(__('File deleted successfully.'));

        // dd($file->delete());

        $file = File::find($id);
        
        // deleted by
        // update kolom deleted_by dengan auth->id

        $file->delete();


        return redirect()->route('files.index')
            ->withSuccess(__('File deleted successfully.'));

    }

    public function download()
    {
        
    }

    public function downloadfile(Request $request, $id)
    {
        // echo $id; exit();

        // get download by/file id and insert into log history
        // code...
        Log::create([
            'user_id' => auth()->user()->id,
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
            'last_download_file_at' => Carbon::now()->toDateTimeString(),
            'last_download_file_id' => $id,
        ]);

        // DB::enableQueryLog();

        $files = File::where('id',$id)->first();

        // $query = DB::getQueryLog();
        // dd($query); exit();

        // echo $files->doc_name; exit();

        $filePath = public_path("file/".$files->doc_name);
        // echo $filePath; exit();

        // $headers = ['Content-Type: application/pdf'];
        $headers = ['Content-Type: '.$files->doc_type];

        $fileName = $files->doc_name;

        return response()->download($filePath, $fileName, $headers);
    }


}
