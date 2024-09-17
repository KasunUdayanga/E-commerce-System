<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Controllers\CustomController;
use App\Offer;
use App\Service;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('offer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $offers = Offer::all();
        $categories = Category::where('status',1)->get();
        $services = Service::where('status',1)->get();
        return view('admin.offer.offer',compact('categories','offers','services'));
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
            'code' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'service_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
            'type' => 'required',
        ]);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $data['category_id'] = implode(',',$data['category_id']);
        $data['service_id'] = implode(',',$data['service_id']);
        Offer::create($data);
        return redirect('admin/offer')->with('msg' , 'Offer created successfully..!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($offer)
    {
        $data = Offer::find($offer)->makeHidden('service');
        $ser_name = '';
        foreach(explode(',',$data->service_id) as $ser_id)
        {
            $service = Service::find($ser_id);
            $ser_name .= $service->service_name .' ';
        }
        $data['ser_name'] = $ser_name;
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($offer)
    {
        $data = Offer::find($offer)->makeHidden(['service']);
        $data['cat_id'] = explode(',',$data->category_id);
        $data['serv_id'] = explode(',',$data->service_id);
        return response(['success' => true , 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $offer)
    {
        $id = Offer::find($offer);
        $data = $request->all();
        $request->validate([
            'code' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'service_id' => 'required',
        ]);
        $data['category_id'] = implode(',',$data['category_id']);
        $data['service_id'] = implode(',',$data['service_id']);
        if ($file = $request->hasfile('image'))
        {
            $request->validate(
            ['image' => 'max:1000'],
            [
                'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
            ]);
            (new CustomController)->deleteImage($id->image);
            $data['image'] = (new CustomController)->uploadImage($request->image);
        }
        $id->update($data);
        return redirect('admin/offer')->with('msg' , 'Offer updated successfully..!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($offer)
    {
        $id = Offer::find($offer);
        (new CustomController)->deleteImage($id->image);
        $id->delete();
        return response(['success' => true]);
    }

    public function offer_category(Request $request)
    {
        $category_ids = $request->category_id;
        $service_ids = [];
        $s = Service::where('status',1)->get();
        foreach ($s as $service)
        {
            foreach ($category_ids as $category_id)
            {
                if(count(array_keys(array_filter(explode(',',$service->category_id)),$category_id))>0)
                {
                    array_push($service_ids,$service->id);
                }
            }
        }
        $services = Service::whereIn('id',$service_ids)->get();
        return response(['success' => true , 'data' => $services]);
    }

    public function update_offer_category(Request $request)
    {
        $category_ids = $request->category_id;
        $service_ids = [];
        $s = Service::where('status',1)->get();
        foreach ($s as $service)
        {
            foreach ($category_ids as $category_id)
            {
                if(count(array_keys(array_filter(explode(',',$service->category_id)),$category_id))>0)
                {
                    array_push($service_ids,$service->id);
                }
            }
        }
        $services = Service::whereIn('id',$service_ids)->get();
        return response(['success' => true , 'data' => $services]);
    }
}
