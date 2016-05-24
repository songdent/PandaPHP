<?php
/**
 * +----------------------------------------------------------------
 * + pandaphp.com [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */
namespace pandaphp\syshelper;

/**
 * 字符串
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Str
{

    /**
     * 将字符串的首字母转换成大写,将下划线去除以及下划线后第一个字母转换成大写,并去掉空字符
     * @access public
     * @param string $str 待转换的字符串
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function toBigHump($str = '')
    {
        $str    = static::stripSpace($str);
        $retStr = ucwords($str);
        if (!empty($str) && false !== strpos($str, '_')) {
            $strSpices = array_map(function ($e) {
                return ucwords($e);
            }, explode('_', $str));
            $retStr    = implode('', $strSpices);
        }
        return $retStr;
    }

    /**
     * 将字符串的首字母转换成小写,将下划线去除以及下划线后第一个字母转换成大写,并去掉空字符
     * @access public
     * @param string $str 待转换的字符串
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function toSmallHump($str = '')
    {
        $str    = static::stripSpace($str);
        $retStr = $str;
        if (!empty($str) && false !== strpos($str, '_')) {
            $strSpices = array_map(function ($e) {
                return ucwords($e);
            }, explode('_', $str));
            $retStr    = implode('', $strSpices);
            $retStr    = lcfirst($retStr);
        }
        return $retStr;
    }

    /**
     * 将字符中的大写字母转换成小写,并在其前面加上下滑线下划线,去掉空字符
     * @access public
     * @param string $str 待转换的字符串
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function toUnderline($str = '')
    {
        $str    = static::stripSpace($str);
        $retStr = $str;
        if (!empty($str)) {
            $underlineChar = '_';
            $strSpices     = [];
            $strLen        = strlen($str);
            for ($i = 0; $i < $strLen; $i++) {
                if (static::isUpperCase($str[ $i ])) {
                    $strSpices[] = $underlineChar . strtolower($str[ $i ]);
                } else {
                    $strSpices[] = $str[ $i ];
                }
            }
            $retStr = implode('', $strSpices);
        }
        return $retStr;
    }

    /**
     * 判断字母是否为大写字母
     * @access public
     * @param string $letter 字母
     * @author songdengtao <http://www.songdengtao.cn>
     * c@return boolean
     */
    public static function isUpperCase($letter = '')
    {
        $retBln = false;
        if (!empty($letter) && strlen($letter) === 1) {
            $upperCases = static::getUpperCases();
            if (in_array($letter, $upperCases)) {
                $retBln = true;
            }
        }
        return $retBln;
    }

    /**
     * 去除字符串中的所有空字符
     * @access public
     * @param string $str
     * @author songdengtao <http://www.songdengtao.cn>
     * c@return string
     */
    public static function stripSpace($str = '')
    {
        $str    = trim($str);
        $retStr = $str;
        if (!empty($str)) {
            $retStr = str_replace(' ', '', $str);
        }
        return $retStr;
    }

    /**
     * 获取26个大写字母数组
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * c@return array
     */
    public static function getUpperCases()
    {
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];
    }

    /**
     * 将字符插入到字符串制定的位置,自定的位移不存在，则不插入
     * @access public
     * @param string $needle 插入的字符
     * @param string $hystack 目标字符串
     * @param int $index 指定的字符串偏移量
     * @author songdengtao <http://www.songdengtao.cn>
     * c@return string
     */
    public static function insert($needle = '', $index = 0, $hystack = '')
    {
        $retStr = $hystack;
        $strLen = strlen($hystack);
        if ($strLen > 0 && $index >= 0 && $index <= $strLen + 1) {
            if ($index === 0) {
                $retStr = $needle . $hystack;
            } elseif ($index === $strLen + 1) {
                $retStr .= $hystack;
            } else {
                $strLeft  = substr($hystack, 0, $index - 1);
                $strRight = str_replace($strLeft, $needle, $hystack);
                $retStr   = $strLeft . $strRight;
            }
        }
        return $retStr;
    }

    /**
     * 给字符串左端添加空字符字符串
     * @access public
     * @param  string $string
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function lblank($string = '')
    {
        if (!empty($string)) {
            return ' ' . trim($string);
        } else {
            return $string;
        }
    }

    /**
     * 获取合格的bindkey 数据库PDO参数绑定的KEY
     * @access public
     * @param  string $search
     * @param  string $repace
     * @param  string $string
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function bindReplace($string = '', $search = '.', $repace = 'dot')
    {
        if (!empty($string)) {
            return str_replace($search, $repace, $string);
        } else {
            return $string;
        }
    }

    /**
     * 子字符串$needle是否在字符串string最末端
     * @access public
     * @param  string $needle
     * @param  string $string
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isEnd($string = '', $needle = '')
    {
        $needleLen = strlen($needle);
        $stringLen = strlen($string);
        if ($needleLen > 0 && $needleLen <= $stringLen) {
            $pos = strrpos($string, $needle);
            if (false !== $pos) {
                return ($pos + $needleLen) === $stringLen ? true : false;
            }
        }
        return false;
    }
}