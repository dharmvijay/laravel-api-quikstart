<?php

namespace App\Http\ResponseTransformers\Location;

use Deliverr\Models\Zone;
use League\Fractal\TransformerAbstract;

/**
 * Created by PhpStorm.
 * User: Khantil Patel
 * Date: 11/08/16
 * Time: 2:06 AM
 */
class ProductTransformer extends TransformerAbstract
{

    public function transform(Zone $zone)
    {
        return [

        ];
    }
}