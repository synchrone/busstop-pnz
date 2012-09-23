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
class Model_Station extends stdClass
{
    //TODO: Variable search radius depending on accuracy
    const RADIUS = 0.37;

    /**
     * @return Model_Station[]
     */
    protected static function fetch()
    {
        return Cache::instance()->get('stations');
    }

    public static function search($query)
    {
        $results = array();

        foreach(self::fetch() as $station)
        {
            if(UTF8::strpos($query,' ')){
                foreach(Text::split_words($query) as $word){
                    if(UTF8::stripos($station->name,$word) !== false){
                        $results[$station->id] = $station;
                    }
                }
            }else if(UTF8::stripos($station->name,$query) !== false)
            {
                $results[] = $station;
            }
        }
        return array_values($results);
    }

    //<editor-fold desc="Nearest stations search">
    protected function floatify($buspnz_latlon){
        return (float)(substr($buspnz_latlon,0,2).'.'.substr($buspnz_latlon,2));
    }
    public function lat(){
        return $this->floatify($this->lat0);
    }
    public function lon(){
        return $this->floatify($this->lon0);
    }
    public static function nearest($lat,$lon)
    {
        $loc = array('lat'=>(float)$lat,'lon'=>(float)$lon);
        $result = array();

        foreach(self::fetch() as $station)
        {
            $miss_distance = sqrt(
                sqrt(abs($station['lat0']-$loc['lat'])) +
                sqrt(abs($station['lon0']-$loc['lon']))
            );
            $inside = $miss_distance <= self::RADIUS;
            if($inside){
                $result[(string)$miss_distance] = $station;
            }
        }
        ksort($result,SORT_NUMERIC);
        return $result;
    }
    //</editor-fold>

    public static function factory($data)
    {
        $object = new self();
        foreach($data as $key=>$value){
            $object->$key = $value;
        }
        return $object;
    }
}