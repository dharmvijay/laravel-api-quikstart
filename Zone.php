<?php

namespace Deliverr\Models\Entities\Location;

use Deliverr\Models\Entities\Traits\GeoPolygonTrait;
use Deliverr\Models\Entities\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Zone extends Model
{

    /**
     * Important: Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zone';
    /**
     * The Primary Key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Important: Decide which columns are editable in the table
     * @var array
     */
    protected $guarded = array('id');
    protected $geoPolygons = array('zone_area');
    protected $slugableColumns = array('name', 'id');

    /**
     * Important: Describe the Inverse One-To-Many relationship with the City Model
     * @return type
     */
    public function city()
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeContains($query, $location)
    {
        return $query->whereRaw('ST_CONTAINS(zone_area, ST_GeomFromText(\'POINT(' . $location . ')\'))');
        //-104.612160 50.410487

    }

    /**
     * Geolocation based parameters and methods.
     */

    use GeoPolygonTrait;

    public function scopeDistance($query, $dist, $location)
    {
        return $query->whereRaw('st_distance(location,POINT(' . $location . ')) < ' . $dist);

    }

    public function scopeEquals($query, $location)
    {
        return $query->whereRaw('ST_Equals(POINT(' . $location . '), latlong)');
    }


    use SlugTrait;

    // E.g., with multiple columns

    public function newQuery($excludeDeleted = true)
    {
        $raw = '';
        foreach ($this->geoPolygons as $column) {
            $raw .= ' astext(' . $column . ') as ' . $column . ' ';
        }

        parent::getAttributeValue($raw);
        return parent::newQuery($excludeDeleted)->addSelect('*', DB::raw($raw));
    }//,'province_id');



    //********General Properties**************//

//    protected $id;
//
//    protected $name;
//
//    protected $city_id;
//
//    protected $slug;
//
//    protected $status;
//
//    protected $zone_area;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Zone
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Zone
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * @param mixed $city_id
     * @return Zone
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Zone
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Zone
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZoneArea()
    {
        return $this->zone_area;
    }

    /**
     * @param mixed $zone_area
     * @return Zone
     */
    public function setZoneArea($zone_area)
    {
        $this->zone_area = $zone_area;
        return $this;
    }


}
