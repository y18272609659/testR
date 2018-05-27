<?php
/**
 * 转换时间戳为游戏内时间
 * @param int $timestamp 游戏时间戳
 * @return string
 */
function gameTime($timestamp)
{
    if (!is_numeric($timestamp)) {
        return 'Error time revert process.';
    }

    $year = 1;
    $month = 1;
    $day = $timestamp / 15;

    if ($day >= 360) {
        $year += intval($day / 360);
        $day %= 360;
    }
    if ($day >= 30) {
        $month += intval($day / 30);
        $day %= 30;
    }

    return $year . '年' . $month . '月' . $day . '日';
}

/**
 * 以某时间为单位，获取其发生数量
 * @param int $timestamp 游戏时间戳
 * @param int $length 时间单位长度，默认 30 天一个单位
 * @return int|string
 */
function gameTimeUnit($timestamp, $length = 30)
{
    if (!is_numeric($timestamp)) {
        return 'Error time revert process.';
    }

    // 300s / 30d / 15s(1d)
    $result = $timestamp / $length / 15;

    return $result;
}

/**
 * 将一个数字按整数小数分割
 * @param int $number 需要分割的数字
 * @return array|string
 */
function exploreTwo($number)
{
    if (!is_numeric($number)) return '参数错误';

    $result = explode('.', $number);
    if (count($result) < 2) {
        $result[1] = '0';
    } else {
        $result[1] = '0.' . $result[1];
    }

    return $result;
}
