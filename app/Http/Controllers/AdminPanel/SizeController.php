<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Size;
use App\Http\Models\Product;
use App\Http\Models\Category;
use GuzzleHttp\Psr7\Request;
use Illuminate\Auth\Access\Response;

class SizeController extends Controller
{

    public function __construct()
    {
        $this->size = new Size();
        $this->product = new Product();
        $this->category = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $output = $this->size->leftJoin($this->category->getTable(), $this->size->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')->get();
        return Response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $input = Request()->all();
        $output = $this->size->create($input);
        $sizeID = $output['SizeID'];
        $output = $this->size->leftJoin($this->category->getTable(), $this->size->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->where($this->size->getTable() . '.SizeID', '=', $sizeID)->get();

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
        return $this->size->where('CategoryID', '=', $id)->get();

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
    public function update($id)
    {
        $input = Request()->all();
        $output = $this->size->find($id)->update($input);
        $output = $this->size
            ->leftJoin($this->category->getTable(), $this->size->getTable() . '.CategoryID', '=', $this->category->getTable() . '.CategoryID')
            ->where($this->size->getTable() . '.SizeID', '=', $id)->get();
        return Response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $check = $this->product->where($this->product->getTable() . '.SizeID', '=', $id)->get();
        if (count($check) > 0) {
            return ['state' => 203];
        } else {
            $this->size->where($this->size->getTable() . '.SizeID', '=', $id)->delete();
            return ['state' => 202];
        }

    }
}
