<?php

namespace Deliverr\Http\ResponseTransformers\Location;

use Deliverr\Models\Entities\Location\City;
use Deliverr\Models\Entities\Location\Zone;
use League\Fractal\TransformerAbstract;

/**
 * Created by PhpStorm.
 * User: Khantil Patel
 * Date: 11/08/16
 * Time: 2:06 AM
 */
class ZoneTransformer extends TransformerAbstract
{

    public function transform(Zone $zone)
    {
        // dd($store->created_at);
        return [
            'zone_id' => $zone->getId(),
            'zone_name' => $zone->getName(),
            'province_id' => $zone->city->province->getId(),
            'province_name' => $zone->city->province->getName(),
            'city_id' => $zone->city->getId(),
            'city_name' => $zone->city->getName(),
            'status' => $zone->getStatus(),
            'polygon_area' => $zone->getZoneArea(),
            'created_at' =>$zone->created_at->toDateTimeString(),
            'updated_at' =>$zone->updated_at->toDateTimeString(),
            // TODO: add Parent model Timestamp updating.
        ];
    }
}