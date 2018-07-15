<?php

namespace App\Http\Controllers\AdminPanel;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\Brand;
use App\Http\Models\Product;
use DB;
use Image;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->product = new Product();
        $this->brand = new Brand();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output =  $this->brand
            ->select($this->brand->getTable().'.*',DB::raw('count('.$this->product->getTable().'.BrandID'.')PrductNo'))
            ->leftjoin($this->product->getTable(),$this->brand->getTable().'.BrandID','=',$this->product->getTable().'.BrandID')
            ->groupby($this->brand->getTable().'.BrandID')
            ->get();
        return Response()->json($output);
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
        $input = Request()->all();
        $baseurl = 'http://ar5ss.com/';
        $realbath = '/var/www/html/ar5ss/public';
        $output = Brand::create($input);
        $id = $output['BrandID'];
        $image = $input["Logo"];
        $jpg_name = "photo-" . time() . ".png";
        $path = $realbath."/Brand/". $jpg_name;

        $input["Logo"] = $baseurl . "Brand/" . $jpg_name;
        $img = substr($image, strpos($image, ",") + 1);//take string after ,
        $imgdata = base64_decode($img);
//        $img = Image::make($imgdata)->resize(60, 20)->save($path, 99);
        $success = file_put_contents($path, $imgdata);
        $output = Brand::find($id)->update($input);
        $output = Brand::where('BrandID', '=', $id)->get();
        return Response()->json($output);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Request()->all();
        if (!Request()->has('Logo')) {
            $output = Brand::find($id)->update([
                "BarndName" => $input["BarndName"],
                "BarndNameE" => $input["BarndNameE"],
            ]);
            $output = Brand::where('BrandID', '=', $id)->get();

        } else {
            $baseurl = 'http://ar5ss.com/';
            $realbath = '/var/www/html/ar5ss/public';
            $image = $input["Logo"];
            $jpg_name = "photo-" . time() . ".jpg";
            $path = $realbath."/Brand/" . $jpg_name;
            $input["Logo"] = $baseurl . "Brand/" . $jpg_name;
            $img = substr($image, strpos($image, ",") + 1);//take string after ,
            $imgdata = base64_decode($img);
            $success = file_put_contents($path, $imgdata);
//            $img = Image::make($imgdata)->resize(100, 40)->save($path, 99);
            $output = Brand::find($id)->update($input);
            $output = Brand::where('BrandID', '=', $id)->get();
        }
        return Response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
