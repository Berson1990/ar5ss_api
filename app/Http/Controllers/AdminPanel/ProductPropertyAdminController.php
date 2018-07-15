<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Models\Category;
use App\Http\Models\Product;
use App\Http\Models\ProductProperty;
use App\Http\Models\PropertyValue;

class ProductPropertyAdminController extends Controller
{
    public function __construct()
    {
        $this->category = new Category();
        $this->product = new Product();
        $this->productProperty = new ProductProperty();
        $this->propertyValue = new PropertyValue();

    }

    public function getProductProperty()
    {
        $output = $this->category
            ->join($this->productProperty->getTable(), $this->category->getTable() . '.CategoryID', '=', $this->productProperty->getTable() . '.CategoryID')
            ->get();

        return Response()->json($output);
    }

    public function storeProductProperty()
    {
        $input = Request()->all();
        $output = $this->productProperty->create($input);
        $ProductPropertyID = $output->ProductPropertyID;

        $output = $this->category
            ->join($this->productProperty->getTable(), $this->category->getTable() . '.CategoryID', '=', $this->productProperty->getTable() . '.CategoryID')
            ->where($this->productProperty->getTable() . '.ProductPropertyID', '=', $ProductPropertyID)
            ->get();
        return Response()->json($output);
    }

    public function update($id)
    {
        $input = Request()->all();
        $this->productProperty->find($id)->update($input);

        $output = $this->category
            ->join($this->productProperty->getTable(), $this->category->getTable() . '.CategoryID', '=', $this->productProperty->getTable() . '.CategoryID')
            ->where($this->productProperty->getTable() . '.ProductPropertyID', '=', $id)
            ->get();
        return Response()->json($output);
    }

    public function delete($id)
    {

        $check = $this->propertyValue->where($this->propertyValue->getTable() . '.ProductPropertyID', '=', $id)->get();

        if (count($check) > 0) {

            return ['state' => 206];
        } else {

            $this->productProperty->where($this->productProperty->getTable() . '.ProductPropertyID', '=', $id)->delete();
            return ['state' => 202];
        }
    }

    public function getPropertyValue($id)
    {

        $output = $this->productProperty
            ->join($this->propertyValue->getTable(), $this->productProperty->getTable() . '.ProductPropertyID', '=', $this->propertyValue->getTable() . '.ProductPropertyID')
            ->join($this->product->getTable(), $this->propertyValue->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->where($this->propertyValue->getTable() . '.ProductID', '=', $id)
            ->groupby($this->propertyValue->getTable() . '.PropertyValueID')
            ->get();
        return Response()->json($output);
    }

    public function addpropertyvaluetoproduct()
    {

        $input = Request()->all();
        $output = $this->propertyValue->create($input);
        $PropertyValueID = $output->PropertyValueID;
        $output = $this->productProperty
            ->join($this->propertyValue->getTable(), $this->productProperty->getTable() . '.ProductPropertyID', '=', $this->propertyValue->getTable() . '.ProductPropertyID')
            ->join($this->product->getTable(), $this->propertyValue->getTable() . '.ProductID', '=', $this->product->getTable() . '.ProductID')
            ->where($this->propertyValue->getTable() . '.PropertyValueID', '=', $PropertyValueID)
            ->get();
        return Response()->json($output);
    }

    public function updatepropertyvalue($id)
    {

        $input = Request()->all();
        $output = $this->propertyValue->find($id)->update($input);
        $output = $this->productProperty
            ->join($this->propertyValue->getTable(), $this->productProperty->getTable() . '.ProductPropertyID', '=', $this->propertyValue->getTable() . '.ProductPropertyID')
            ->where($this->propertyValue->getTable() . '.PropertyValueID', '=', $id)
            ->get();
        return Response()->json($output);
    }

    public function deletepropertyvalue($id)
    {
        $this->propertyValue->where($this->propertyValue->getTable() . '.PropertyValueID', '=', $id)->delete();
        return ['state' => '202'];

    }

//    fill cbo
    public function getProrpertyForCategory($id)
    {
        $output = $this->productProperty
//            ->join($this->productProperty->getTable(), $this->category->getTable() . '.CategoryID', '=', $this->productProperty->getTable() . '.CategoryID')
            ->where($this->productProperty->getTable() . '.CategoryID', '=', $id)
            ->get();
        return Response()->json($output);
    }


}
