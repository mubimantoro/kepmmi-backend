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
        $pamflets = Pamflet::latest()->paginate(9);

        return new PamfletResource(true, 'List data Pamflet', $pamflets);
    }
}
