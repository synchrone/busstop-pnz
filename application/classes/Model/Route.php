<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @property int $id : 11,
 * @property string $name : "Тр- 1",
 * @property string $type : "Т",
 * @property string $num : "1",
 * @property string $fromst : "пл.Маршала Жукова",
 * @property string $fromstid : 173,
 * @property string $tost : "Северная",
 * @property string $tostid : 405
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
            if($route->id == $id)
            {
                return $route;
            }
        }

        return null;
    }
}