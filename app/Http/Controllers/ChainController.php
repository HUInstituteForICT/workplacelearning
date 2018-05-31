<?php


namespace App\Http\Controllers;


use App\ChainManager;
use App\Http\Requests\Chain\CreateRequest;

class ChainController extends Controller
{


    public function create(CreateRequest $request, ChainManager $chainManager)
    {
        return $chainManager->createChain($request->get('name'));
    }
}