<?php

namespace App\Classes\Pager;

use Abort;
use DB;
use Log;
use Illuminate\Database\Eloquent\Builder;
use App\Classes\Pager\QueryStringParser;


class Pager
{
    private $model;
    private $queryObject;
    private $modelClassName;

    private $defaultPageNumber = 1;
    private $defaultPageSize;
    private $maxSize;
    private $sortDefault;

    private $pageIndex;
    private $pageSize;

    private $paginator;
    private $totalCount;
    private $currentPage;
    private $collection;

    public function __construct(Builder $model)
    {
        $this->model = $model;
        $this->modelClassName = get_class($model->getModel());
        $this->maxSize = config("pager.default.pageSize.max");
        $this->defaultPageSize = config("pager.default.pageSize.basic");
        $this->sortDefault = config("pager.default.sort");
    }

    public function setQueryObject($requestQuery = null)
    {
        $parser = new QueryStringParser();
        $this->queryObject = $parser->parse($requestQuery)->get();
        $this->pageIndex = $this->_setPageIndex($requestQuery);
        $this->pageSize = $this->_setPageSize($requestQuery);
        return $this;
    }

    // TODO :: inject query object

    public function setQuery()
    {
//        Log::info( print_r($this->queryObject,true) );
        foreach ($this->queryObject as $index => $object) {
            if ($this->_isSameModel($object)) {
                // 바로 쿼리
                switch ($object['type']){
                    case "filter" : $this->model = $this->_directWhere($this->model,$object); break;
                    case "in" : $this->model = $this->_directWhereIn($this->model,$object); break;
                    case "range" : $this->model = $this->_directWhereBetween($this->model,$object); break;
                    case "sort" : $this->model = $this->_directOrder($this->model,$object); break;
                    default : Abort::Error("0040"); break;
                }
            } else {
                // where has 쿼리
                switch ($object['type']){
                    case "filter" : $this->model = $this->_directWhereHas($this->model,$object); break;
                    case "in" : $this->model = $this->_directWhereHasIn($this->model,$object); break;
                    case "range" : $this->model = $this->_directWhereHasBetween($this->model,$object); break;
                    case "sort" : $this->model = $this->_directWhereHas($this->model,$object); break;
                    default : Abort::Error("0040"); break;
                }
            }
        }
        return $this;
    }

    private function _directWhere(Builder $model,$object){
        return $model->where($object['column'], $object['comparison'], $object['value']);
    }

    private function _directWhereHas(Builder $model,$object){
        return $model->whereHas($object['relation'], function (Builder $query) use ($object) {
            $query->where($object['column'], $object['comparison'], $object['value']);
        });
    }

    private function _directWhereIn(Builder $model,$object){
        return $model->whereIn($object['column'], $object['value']);
    }

    private function _directWhereHasIn(Builder $model,$object){
        return $model->whereHas($object['relation'], function (Builder $query) use ($object) {
            $query->whereIn($object['column'], $object['value']);
        });
    }

    private function _directWhereBetween(Builder $model,$object){
        return $model->whereBetween($object['column'], $object['value']);
    }

    private function _directWhereHasBetween(Builder $model,$object){
        return $model->whereHas($object['relation'], function (Builder $query) use ($object) {
            $query->whereBetween($object['column'], $object['value']);
        });
    }

    private function _directOrder(Builder $model,$object){
        return $model->orderBy($object['column'], $object['value']);
    }

    private function _directOrderHas(Builder $model,$object){
        // TODO
        Abort::Error('0052',"not yet...");
//        return $model->whereHas($object['relation'], function (Builder $query) use ($object) {
//            $query->orderBy($object['column'], $object['comparison'], $object['value']);
//        });
    }

    private function _isSameModel($queryObject)
    {
        return $queryObject['model'] === $this->modelClassName;
    }

    private function _setPageIndex($requestQuery)
    {
        return isset($requestQuery['pageIndex'])
            ? $requestQuery['pageIndex']
            : $this->defaultPageNumber;
    }

    private function _setPageSize($requestQuery)
    {
        return isset($requestQuery['pageSize'])
            ? $this->_checkMaxSize($requestQuery['pageSize'])
            : $this->defaultPageSize;
    }

    public function withTrashed(){
        $this->model->withTrashed();
        return $this;
    }

    private function _checkMaxSize($pageSize){
        return $pageSize <= $this->maxSize ? $pageSize : $this->maxSize;
    }

    public function getCollection()
    {
        $this->paginator = $this->model->paginate($this->pageSize, ['*'], 'page', $this->pageIndex);
        $this->totalCount = $this->paginator->total();
        $this->currentPage = $this->paginator->currentPage();
        $this->collection = $this->paginator->getCollection();
        return $this->collection;
    }

    public function getPageInfo()
    {
        return (object)[
            "totalCount"  => $this->totalCount,
            "currentPage" => $this->currentPage,
        ];
    }
}
