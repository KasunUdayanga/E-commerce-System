<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomController;
use App\Language;
use App\NotificationTemplate;
use App\User;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use File;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('language_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $languages = Language::orderBy('id','DESC')->get();
        return view('admin.language.language',compact('languages'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,svg',
            'direction' => 'required',
            'file' => 'bail|required'
        ]);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $file = $request->file('image');
            $fileName = $request->name;
            $path = public_path('/images/upload/');
            $file->move($path, $fileName.".png");
            $data['image'] = $fileName.".png";
        }
        if ($file = $request->hasfile('file'))
        {
            $file = $request->file('file');
            $fileName = $request->name;
            $path = resource_path('/lang');
            $file->move($path, $fileName.'.json');
            $data['file'] = $fileName.".json";;
        }
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 0;
        }
        Language::create($data);
        return redirect('admin/language');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($language)
    {
        $data = Language::find($language);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language)
    {
        $data = $request->all();
        $id = Language::find($language);
        $request->validate([
            'name' => 'required',
            // 'file' => 'required|mimes:json',
            // 'image' => 'required|mimes:png,jpg,jpeg,svg',
            'direction' => 'required',
        ]);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $file = $request->file('image');
            $fileName = $request->name;
            $path = public_path('/images/upload/');
            $file->move($path, $fileName.".png");
            $data['image'] = $fileName.".png";
        }
        if ($file = $request->hasfile('file'))
        {
            $file = $request->file('file');
            $fileName = $request->name;
            $path = resource_path('/lang');
            $file->move($path, $fileName.'.json');
            $data['file'] = $fileName.".json";;
        }
        $id->update($data);
        return redirect('admin/language')->with('msg','Update Language successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($language)
    {
        $id = Language::find($language);
        (new CustomController)->deleteImage($id->image);
        if(File::exists(resource_path('/lang'.'/'.$id->file))){
            File::delete(resource_path('/lang'.'/'.$id->file));
        }
        $id->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Language::find($request->id);
        if($data->status == 0)
        {
            $data->status = 1;
            $data->save();
            return response(['success' => true]);
        }
        if($data->status == 1)
        {
            $data->status = 0;
            $data->save();
            return response(['success' => true]);
        }
    }

}
