<?php

namespace Deliverr\Http\Controllers\Admin\Location;

use Deliverr\Http\Requests\Admin\ZoneRequest;
use Deliverr\Http\Requests\Admin\ZoneUpdateRequest;
use Deliverr\Http\Response\APIResponseV2;
use Deliverr\Http\ResponseTransformers\Location\CityTransformer;
use Deliverr\Http\ResponseTransformers\Location\ZoneTransformer;
use Deliverr\Models\Entities\Location\Zone;
use Deliverr\Models\Entities\StatusEnum;
use Deliverr\Services\Location\ZoneService;
use Illuminate\Http\Request;

use Deliverr\Http\Requests;
use Deliverr\Http\Controllers\Controller;
use League\Fractal\Serializer\ArraySerializer;

class ZoneController extends Controller
{

    protected $deliverrResponse = null;
    protected $zoneService = null;

    /**
     * CityController constructor.
     * @param APIResponseV2 $deliverrResponse
     */
    public function __construct(APIResponseV2 $deliverrResponse)
    {
        $this->deliverrResponse = $deliverrResponse;
        $this->zoneService = new ZoneService();
    }

    /**
     * @param ZoneRequest $request
     * @return mixed|null
     * @throws \Exception
     */
    public function addZone(ZoneRequest $request)
    {
        $response = null;

        \DB::beginTransaction();

        try {

            $this->zoneService->addZone(
                $request->input('city_id'),
                $request->input('name'),
                $request->input('status'),
                $request->input('polygon_area')
            );

            $response = $this->deliverrResponse->respondCreated("Zone Created!");

        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }

        \DB::commit();
        return $response;
    }


    public function updateZone($admin_id, $zone_id, ZoneUpdateRequest $request)
    {
        $response = null;

        \DB::beginTransaction();

        try {

            $zone = Zone::id($zone_id)->firstorfail();

            $this->zoneService->updateZone(
                $zone,
                $request->input('name'),
                $request->input('status'),
                $request->input('polygon_area')
            );

            $response = $this->deliverrResponse->respondUpdated("Zone updated.");

        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }

        \DB::commit();
        return $response;
    }

    public function getZone($admin_id, $zone_id)
    {
        $response = null;

        try {

            $zone = Zone::id($zone_id)->firstorfail();

            $data = fractal()
                ->item($zone, new ZoneTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray();
            $response = $this->deliverrResponse->respondWithData($data);

        } catch (\Exception $ex) {

            throw $ex;
        }
        
        return $response;
    }


    public function getZoneList()
    {
        if (\Input::has('active')) {
            $zones = Zone::active()
                ->get();

        } else {
            $zones = Zone::get();
        }

        $data = fractal()
            ->collection($zones, new ZoneTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();

        return $this->deliverrResponse->respondWithData($data['data']);
    }

    public function changeZoneStatus($admin_id , $zone_id , Request $request){

        $response = null;
        \DB::beginTransaction();
        try {

            $this->validate($request, [

                'status' => 'required|in:' . implode(',', StatusEnum::getEnumArray()) ,

            ]);

            $zone = Zone::id($zone_id)->firstorfail();
            $zone->setStatus($request->get("status"));
            $zone->save();
            $response = $this->deliverrResponse->respondUpdated("Zone status changed");

        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
        \DB::commit();
        return $response;
    }


}
