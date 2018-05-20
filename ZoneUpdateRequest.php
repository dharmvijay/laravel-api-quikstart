<?php

namespace Deliverr\Http\Requests\Admin;

use Deliverr\Http\Requests\Request;

class ZoneUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'status' => 'required|string|in:active,inactive',
            'polygon_area' => 'required|array', //'49.2462,-123.1162'
        ];
    }
}
