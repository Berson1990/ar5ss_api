<?php

namespace App\Http\Controllers;

use App\Http\Models\ProductProperty;
use App\Http\Models\PropertyValue;

class ProductPropertyController extends Controller
{
    //
    public function __construct()
    {
        $this->productProperty = new ProductProperty();
        $this->propertyValue = new PropertyValue();
    }

    public function createNewPropductProperty()
    {
        $input = Request()->all();
        $output = $this->productProperty->create($input);
        return Response()->json($output);
    }

    public function getProductProperty($id)
    {

        $output = $this->productProperty->with('ProductValue')
            ->leftjoin($this->propertyValue->getTable(), $this->productProperty->getTable() . '.ProductPropertyID', '=', $this->propertyValue->getTable() . '.ProductPropertyID')
            ->where($this->propertyValue->getTable() . '.ProductID', '=', $id)
            ->get();
        return Response()->json($output);

    }
}
