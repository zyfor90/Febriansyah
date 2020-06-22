<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Helper;

class BookController extends Controller
{

    use Helper;

    public function index()
    {
        $data = $this->modelBook::orderBy('id', 'desc')->get();

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'cover' => 'required',
        ]);

        $path = '';
        if ($request->file('cover')) {
            $path   = $this->images($request->file('cover'));
        }

        $this->modelBook::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'cover' => $path,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Buku Berhasil di tambahkan'], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'cover' => 'required',
        ]);
        
        $path = '';
        if ($request->file('cover')) {
            $path   = $this->images($request->file('cover'));
        }

        $this->modelBook::find($request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'cover' => $path,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Buku Berhasil di edit'], 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $this->modelBook::find($request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Buku Berhasil di hapus'], 200);
    }

    //Image Upload
    public function images($file)
    {
        // get file type
        $extension_type   = $file->getClientMimeType();
        // set storage path to store the file (actual video)
        $destination_path = public_path('images/cover');
        // get file extension
        $extension        = $file->getClientOriginalExtension();
        // rename
        $timestamp        = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $file_name        = $timestamp;
        // move to laravel directory
        $moving           = $file->move($destination_path, $file_name.'.'.$extension);
        // Set Path for database use
        $path             = 'images/cover/'.$file_name.'.'.$extension;

        return $path;
    }
}
