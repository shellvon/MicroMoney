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

    /**
     * 获取友好的时间描述,比如多少分钟前.
     *
     * @param int $from_time
     * @param int $to_time 如果为null,默认取当前时间.
     *
     * @return string
     */
    public static function friendlyTimeAgo($from_time, $to_time = null)
    {
        if ($to_time == null) {
            $to_time = time();
        }
        $is_future = ($to_time < $from_time);
        if ($is_future) {
            throw  new \LogicException('不支持和将来相比较.');
        }
        $distance_in_minutes = round((abs($to_time - $from_time))/60);
        $distance_in_seconds = round(abs($to_time - $from_time));

        if ($distance_in_minutes < 1) {
            return sprintf('大约%s秒前', $distance_in_seconds);
        }
        if ($distance_in_minutes < 60) {
            return sprintf('大约%s分钟前', $distance_in_minutes);
        }
        if ($distance_in_minutes < 1440) {
            return sprintf('大约%s小时前', round($distance_in_minutes/60));
        }
        $distance_in_days = round($distance_in_minutes/1440);
        if ($distance_in_days <= 30) {
            return sprintf('大约%s天前', $distance_in_days);
        }
        if ($distance_in_days < 345) {
            return sprintf('大约%个月前', round($distance_in_days/30));
        }
        return sprintf('大约%s年前',round($distance_in_days/365));
    }
}
