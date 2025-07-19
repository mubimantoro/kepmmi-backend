<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PamfletResource;
use App\Models\Pamflet;
use Illuminate\Http\Request;

class PamfletController extends Controller
{
    public function index()
    {
        $pamflet = Pamflet::latest()->paginate(9);

        return new PamfletResource(true, 'List data Pamflet', $pamflet);
    }

    public function show($id)
    {
        $pamflet = Pamflet::whereId($id)->first();

        if ($pamflet) {
            //return with Api Resource
            return new PamfletResource(true, 'Detail data Pamflet', $pamflet);
        }

        //return with Api Resource
        return new PamfletResource(false, 'Detail data Pamflet tidak ditemukan!', null);
    }

    public function homePage()
    {
        $pamflet = Pamflet::latest()->take(6)->get();

        //return with Api Resource
        return new PamfletResource(true, 'List data Pamflet HomePage', $pamflet);
    }
}
