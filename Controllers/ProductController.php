<?php

namespace App\Http\Controllers;

use App\Http\Response\APIResponseV2;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $deliverrResponse = null;

    /**
     * CityController constructor.
     * @param APIResponseV2 $deliverrResponse
     */
    public function __construct(APIResponseV2 $deliverrResponse)
    {
        $this->deliverrResponse = $deliverrResponse;
    }

    public function index()
    {
        $response = null;

        \DB::beginTransaction();

        try {

            $response = $this->deliverrResponse->respondCreated("Zone Created!");

        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }

        \DB::commit();
        return $response;
    }


}
