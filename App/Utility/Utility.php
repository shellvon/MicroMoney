<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/19
 * Time: 下午3:21.
 */

namespace Utility;

class Utility
{
    /**
     * @param $array
     * @param int $limit
     *
     * @return array
     */
    public static function combination($array, $limit = 2)
    {
        $results = array(array());
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        $results = array_values(array_filter($results, function ($element) use ($limit) {return count($element) >= $limit;}));
        usort($results, function ($a, $b) {
            return count($a) > count($b);
        });

        return $results;
    }
}
