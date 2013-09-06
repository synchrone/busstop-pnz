<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property string $formal_name
 * @property string $full_name
 * @property string $name
 * @property string $start
 * @property string $finish
 * @property string $type
 * @property string $id1
 * @property string $id2
 */
class Model_Route extends Model
{
    /**
     * @return Model_Route[]
     */
    protected static function fetch()
    {
        return Cache::instance()->get('routes');
    }

    public static function by_id($id)
    {
        foreach(self::fetch() as $route)
        {
            if($route->id1 == $id || $route->id2 == $id)
            {
                return $route;
            }
        }

        return null;
    }
}

/**
 * @property int $typeId
 * @property string typeName
 * @property string typeShName (А, Т, М)
 */
class Model_Route_Type extends Model{

}