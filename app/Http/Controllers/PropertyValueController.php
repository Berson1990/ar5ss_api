<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\PropertyValue;


class PropertyValueController extends Controller
{
    public function __construct()
    {
        $this->propertyValue = new PropertyValue();
    }

    public function createNewPropetyValue(){
        $input = Request()->all();
        $output = $this->propertyValue->create($input);
        return Response()->json($output);

    }

}
