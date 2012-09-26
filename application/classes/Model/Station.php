<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property int $type
 * @property int $id
 * @property string $name
 * @property int $lon0
 * @property int $lat0
 * @property int $lon1
 * @property int $lat1
 * @property int $end
 * @property string $heading
 */
class Model_Station extends Model
{
    public $heading;

    const MATCH_ALL = 'match_all';
    const MATCH_ANY = 'match_any';

    //TODO: Variable search radius depending on accuracy
    const RADIUS = 0.35;
    const MIN_ACCURACY = 1500;

    /**
     * @return Model_Station[]
     */
    protected static function fetch()
    {
        return Cache::instance()->get('stations');
    }

    public static function search($query,$match_type)
    {
        $results = array();
        foreach(self::fetch() as $station)
        {
            if($station->$match_type($query)){
                $results[$station->id] = $station;
            }
        }
        return array_values($results);
    }

    public static function nearest($lat,$lon,$accuracy)
    {
        if($accuracy > self::MIN_ACCURACY){ return array();}

        $loc = array('lat'=>(float)$lat,'lon'=>(float)$lon);
        $result = array();

        foreach(self::fetch() as /** @var Model_Station $station */ $station)
        {
            $miss_distance = sqrt(
                sqrt(abs($station->lat() - $loc['lat'])) +
                sqrt(abs($station->lon() - $loc['lon']))
            );
            $inside = $miss_distance <= self::RADIUS;
            if($inside){
                $result[(string)$miss_distance] = $station;
            }
        }
        ksort($result,SORT_NUMERIC);
        return $result;
    }

    public static function by_id($id,$single=false)
    {
        $id = (array)$id;
        if(empty($id)){return array();}

        $stations = array();
        foreach(self::fetch() as /** @var $station Model_Station */ $station)
        {
            if(in_array($station->id,$id))
            {
                if($single){return $station;}
                $stations[] = $station;
            }
        }
        return $stations;
    }


    protected function floatify($buspnz_latlon){
        return (float)(substr($buspnz_latlon,0,2).'.'.substr($buspnz_latlon,2));
    }
    public function lat(){
        return $this->floatify($this->lat0);
    }
    public function lon(){
        return $this->floatify($this->lon0);
    }

    protected function match_all($query)
    {
        foreach(Text::split_words($query) as $word)
        {
            if(UTF8::stripos($this->name,$word) === false)
            {
                return false;
            }
        }
        return true;
    }
    protected function match_any($query){
        foreach(Text::split_words($query) as $word)
        {
            if(UTF8::stripos($this->name,$word) !== false)
            {
                return true;
            }
        }
        return false;
    }
}