<?php

namespace App\Classes\Pager;

// Global
use DB;
use Log;
use Abort;

class QueryStringParser
{
    // DOCS
    // https://lubycon.atlassian.net/wiki/spaces/DEV/pages/860396/Querystring+Filter
    // 쿼리스트링에 대한 참조 문서

    private $queryString;
    private $divider;
    private $dividerRegex;
    private $sections;
    private $object = [];

    public function __construct()
    {
        $this->divider = config("pager.comparision.divider");
        $this->dividerRegex = config("pager.comparision.regex");
        $this->sections = config("pager.sections");
    }

    public function parse($queryString)
    {
        foreach ($queryString as $sectionName => $value) {
            if ($this->_isAvailableSection($sectionName)) {
                $parse = $this->_getQueryObject($sectionName, $queryString[$sectionName]);
                $this->object = array_merge_recursive($this->object, $parse);
            }
        }
        return $this;
    }

    public function get()
    {
        return $this->object;
    }

    private function _getQueryObject($sectionName, $query)
    {
        $result = [];
        $divideQuery = explode($this->divider, $query);
        foreach ($divideQuery as $key => $value) {
            $queryString = urldecode($value);

            // Split divider
            $splits = preg_split($this->dividerRegex, $queryString);
            $comparison = $this->_getComparision($queryString, $splits);

            // validation comparision.
            $this->_validateComparision($sectionName,$comparison);

            // get object information
            $key = $this->_keyConversion($splits[0]);
            $value = $this->_valueConversion($splits[1]);
            $type = $this->_getType($sectionName, $comparison ,$value);

            // For RangeQuery
            if( $type === 'range' ){
                $value = $this->_explodeRangeValue($value);
            }else if( $type === 'in' ){
                $value = $this->_decodeInValue($value);
            }

            $queryObject = $this->_createQueryObject($key, $value, $comparison, $type);
            // inject result
            if (!is_null($type)) {
                $result[] = $queryObject;
            } else {
                Abort::Error('0040', 'Unknown Type');
            }
        }
        return $result;
    }

    private function _validateComparision($sectionName,$comparision){
        $comparisions = config("pager.comparision.$sectionName");
        if(!in_array( $comparision,$comparisions)){
            return Abort::Error('0040','Invalid Comparision');
        };
        return true;
    }

    private function _keyConversion($key)
    {
        $checked = $this->_getConversion('key', $key);
        if (is_null($checked)) return $key;
        return $checked;
    }

    private function _valueConversion($value)
    {
        $checked = $this->_getConversion('value', $value);
        if (is_null($checked)) return $value;
        return $checked;
    }

    private function _getConversion($type, $value)
    {
        return config("pager.conversion.$type.$value");
    }

    private function _getComparision($subject, $search)
    {
        // 실제 쿼리에서 사용될 Comparision
        // :를 =로 치환
        $result = str_replace($search, '', $subject);
        $result = $result[0];
        return $result === ":" ? "=" : $result;
    }

    private function _explodeRangeValue($value){
        $result = explode("~",$value);
        return is_array($result) ? $result : Abort::Error("0040","Range Query Explode Error");
    }

    private function _decodeInValue($value){
        $result =  json_decode($value);
        return is_array($result) ? $result : [$result];
    }

    private function _getType($sectionName,$comparison, $value)
    {
        // filter = < > <= >= :
        // search :
        // in ;
        // range ~
        // sort :
        $result = null;
        if ($sectionName === "filter") {
            if( strpos($value,'~') ){
                $result = "range";
            }else if( $comparison === ';' ){
                $result = "in";
            }else{
                $result = "filter";
            }
        } else {
            $result = $sectionName;
        }
        return $result;
    }

    private function _isAvailableSection($sectionName)
    {
        return in_array($sectionName, $this->sections);
    }

    private function _createQueryObject($key, $value, $comparison, $type)
    {
        return [
            "type"     => $type,
            "model"    => $key["model"],
            "relation" => $key["relation"],
            "column"   => $key["column"],
            "value"    => $value,
            "comparison" => $comparison,
        ];
    }
}
