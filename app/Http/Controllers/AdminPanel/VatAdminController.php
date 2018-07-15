<?php

namespace App\Http\Controllers\AdminPanel;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Models\Vat;

class VatAdminController extends Controller
{
    //

    public function updateVat($id)
    {

        $input = Request()->all();
        $output = Vat::where('id', $id)->update($input);
        return $input['value'];

    }
}
