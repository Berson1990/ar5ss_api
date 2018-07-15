<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\Category;
use App\Http\Models\Brand;
use App\Http\Models\Product;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->product = new Product();
        $this->categroy = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $output = $this->categroy
            ->select($this->categroy->getTable() . '.*', DB::raw('count(' . $this->product->getTable() . '.CategoryID' . ')PrductNo'))
            ->leftjoin($this->product->getTable(), $this->categroy->getTable() . '.CategoryID', '=', $this->product->getTable() . '.CategoryID')
            ->groupby($this->categroy->getTable() . '.CategoryID')
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Request()->all();
        $baseurl = 'http://ar5ss.com/';
        $realbath = '/var/www/html/ar5ss/public';
        $output = $this->categroy->create($input);
        $id = $output['CategoryID'];
        $image = $input["CategoryImage"];

        $jpg_name = "photo-" . $id . ".jpg";

        $path = $realbath . "/CategoryImage/" . $jpg_name;

        $input["Image"] = $baseurl . "CategoryImage/" . $jpg_name;

        $img = substr($image, strpos($image, ",") + 1);//take string after ,
        $imgdata = base64_decode($img);
        $success = file_put_contents($path, $imgdata);
//        $img = Image::make($imgdata)->resize(100, 40)->save($path, 99);
        $output = $this->categroy->find($id)->update([
            "CategoryImage" => $input["Image"]
        ]);
        $output = $this->categroy->where('CategoryID', '=', $id)->get();
        return Response()->json($output);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Request()->all();
        if ($input['CategoryImage'] == '') {
            $output = Category::find($id)->update([
                "categroy_name" => $input["categroy_name"],
                "categroy_nameen" => $input["categroy_nameen"],
            ]);
            $output = Category::where('CategoryID', '=', $id)->get();
            return Response()->json($output);

        } else {
            $baseurl = 'http://ar5ss.com/';
            $realbath = '/var/www/html/ar5ss/public';
            $image = $input["CategoryImage"];
            $jpg_name = "photo-" . time() . ".jpg";
            $path = $realbath . "/CategoryImage/" . $jpg_name;
            $input["CategoryImage"] = $baseurl . "CategoryImage/" . $jpg_name;
            $img = substr($image, strpos($image, ",") + 1);//take string after ,
            $imgdata = base64_decode($img);
            $success = file_put_contents($path, $imgdata);
            $output = $this->categroy->find($id)->update($input);
            $output = $this->categroy->where('CategoryID', '=', $id)->get();
            return Response()->json($output);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function HideCategory($id)
    {
        $this->categroy->find($id)->update([
            "Hide" => '1'
        ]);
    }
}
