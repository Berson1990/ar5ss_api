<?php

namespace App\Http\Controllers;

use App\Http\Models\Location;

class LoactionController extends Controller
{
    public function setLocation()
    {
        $input = Request()->all();
        $output = Location::create($input);
        return Response()->json($output);
    }

    public function getLocationForUser($id)
    {
        $output = Location::where('UserID', '=', $id)->get();
        return Response()->json($output);
    }

    public function setDefaultLoctionForUser($id)
    {
        //set new loction
        $output = Location::where('LocationID', '=', $id)
            ->update(['Defualt' => 1]);

        //get user Id for this location Id
        $output = Location::where('LocationID', '=', $id)->get();
        foreach ($output as $output) {
            $UserID = $output->UserID;
        }

        $output = Location::where('UserID', '=', $UserID)
            ->where('Defualt', '=', 1)
            ->update(['Defualt' => 0]);
        return ['state' => '202'];
    }

    public function destoryLocation($id)
    {

        Location::where('LocationID', '=', $id)->delete();
        return ['stute' => '202'];
    }
}
