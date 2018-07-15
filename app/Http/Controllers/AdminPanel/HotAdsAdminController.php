<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Models\HotAdds;
use App\Http\Models\SliderChangeMode;
use Image;


class HotAdsAdminController extends Controller
{
    //

    public function __construct()
    {
        $this->hotads = new HotAdds();
        $this->changmode = new SliderChangeMode();
    }

    public function index()
    {
        $output = $this->hotads->all();
        return Response()->json($output);
    }

    public function store()
    {
        $input = Request()->all();
        $baseurl = 'http://ar5ss.com/';
        $realbath = '/var/www/html/ar5ss/public';
        $output = $this->hotads->create($input);
        $id = $output->HotAdsID;
        $image = $input["Image"];
        $jpg_name = "photo-" . time() . ".png";
        $path = $realbath . "/HotAds/" . $jpg_name;
        $input["Image"] = $baseurl . "HotAds/" . $jpg_name;
        $img = substr($image, strpos($image, ",") + 1);//take string after ,
        $imgdata = base64_decode($img);
//        $img = Image::make($imgdata)->resize(60, 20)->save($path, 99);
        $success = file_put_contents($path, $imgdata);
        $output = $this->hotads->find($id)->update($input);
        $output = $this->hotads->where('HotAdsID', '=', $id)->get();
        return Response()->json($output);
    }

    public function update($id)
    {
        $input = Request()->all();
        $baseurl = 'http://188.226.135.249/';
        $realbath = '/var/www/html/ar5ss/public';
        $image = $input["Image"];
        $jpg_name = "photo-" . time() . ".png";
        $path = $realbath . "/HotAds/" . $jpg_name;
        $input["Image"] = $baseurl . "HotAds/" . $jpg_name;
        $img = substr($image, strpos($image, ",") + 1);//take string after ,
        $imgdata = base64_decode($img);
//        $img = Image::make($imgdata)->resize(60, 20)->save($path, 99);
        $success = file_put_contents($path, $imgdata);
        $output = $this->hotads->find($id)->update($input);
        $output = $this->hotads->where('HotAdsID', '=', $id)->get();
        return Response()->json($output);

    }

    public function delete($id)
    {
        $this->hotads->where('HotAdsID', '=', $id)->delete();
        return ['state' => 202];
    }

    public function ChangeMode()
    {
        $input = Request()->all();
        $id = 1;
        $this->changmode->find($id)->update([
            "Mode" => $input['Mode']
        ]);
        $output = $this->changmode->all();
        return Response()->json($output);
    }


    public function getChngeMode()
    {
        $output = $this->changmode->all();
        return Response()->json($output);

    }

    public function AssginProductOrCategoryForHotAdds($id)
    {
        $input = Request()->all();

        $output = $this->hotads->where($this->hotads->getTable() . '.HotAdsID', '=', $id)->update([
            "CategoryID" => $input['CategoryID'],
            "ProductID" => $input['ProductID']
        ]);
        return $output;
    }
}
