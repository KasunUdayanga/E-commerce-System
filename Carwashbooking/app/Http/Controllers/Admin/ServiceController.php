<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Appointment;
use App\Category;
use App\Coworkers;
use App\Http\Controllers\CustomController;
use App\Service;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = Service::all();
        $categories = Category::where('status',1)->get();
        $coworkers = Coworkers::where('status',1)->get();
        return view('admin.services.service',compact('coworkers','categories','services'));
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
        $request->validate([
            'service_name' => 'required',
            'category_id' => 'required',
            'coworker_id' => 'required',
            'price' => 'bail|required|numeric',
            'description' => 'required',
            'duration' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        $data['category_id'] = implode(',',$data['category_id']);
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 1;
        }
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        Service::create($data);
        return redirect('admin/service')->with('msg','Service created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($service)
    {
        $data = Service::with('coworker')->where('id',$service)->first()->makeHidden(['coworker']);
        $data->co_worker = Coworkers::find($data->coworker_id)->makeHidden(['service']);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($service)
    {
        $service = Service::find($service)->makeHidden(['coworker']);
        return response()->json(['success' => true , 'data' => $service]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $service)
    {
        $request->validate([
            'service_name' => 'required',
            'category_id' => 'required',
            'coworker_id' => 'required',
            'price' => 'bail|required|numeric',
            'description' => 'required',
            'duration' => 'bail|required|numeric',
        ]);
        $data = $request->all();
        $id = Service::find($service);
        $data['category_id'] = implode(',',$data['category_id']);
        if(isset($data['status']))
        {
            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 1;
        }
        if ($file = $request->hasfile('image')) {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage($id->image);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $id->update($data);
        return redirect('admin/service')->with('msg','Service updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($service)
    {
        $appointment = Appointment::all();
        foreach ($appointment as $value)
        {
            $services = explode(',',$value->service_id);
            if (($key = array_search($service, $services)) !== false)
            {
                return response(['success' => false , 'data' => 'This service connected with Appointment first delete appointment']);
            }
        }

        $id = Service::find($service);
        (new CustomController)->deleteImage($id->image);
        $id->delete();
        return response(['success' => true]);
    }

    public function change_status(Request $request)
    {
        $data = Service::find($request->id);
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
