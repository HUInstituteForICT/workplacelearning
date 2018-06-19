<?php


namespace App\Http\Controllers;


use App\Chain;
use App\ChainManager;
use App\Http\Requests\Chain\CreateRequest;
use App\Http\Requests\Chain\SaveRequest;

class ChainController extends Controller
{


    public function create(CreateRequest $request, ChainManager $chainManager)
    {
        return $chainManager->createChain($request->get('name'));
    }

    public function save(SaveRequest $request, ChainManager $chainManager, Chain $chain)
    {
        $chainManager->updateChain($chain, $request->get('name'), $request->get('status'));

        return $chain;
    }
}