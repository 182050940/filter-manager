<?php

class FilterManager{

    const ALL = 'FilterManager_All';

    /**
     * current filters 筛选器
     * exp:['gender'=>'male','city'=>'beijing',...]
     * @var array
     */
    protected  $filters = [];

    /**
     * blacklist 黑名单
     * @var array
     */
    protected  $blackList = [];

    /**
     * boot url 不带参数url
     *  without params,exp:'www.xxx.com/goods'
     * @var string
     */
    protected  $bootUrl = "";


    public function __construct(Array $filters,$bootUrl,Array $blackList){
        $this->filters   = $filters;
        $this->bootUrl   = $bootUrl;
        $this->blackList = $blackList;
    }


    public static function create(Array $filters,$bootUrl = '/',Array $blackList = ['page','pageindex']){
        $fm = new FilterManager($filters,$bootUrl,$blackList);
        return $fm;
    }


    public function setBootUrl($bootUrl){
        $this->bootUrl->$bootUrl;
        return $this;
    }


    public function setBlackList(Array $blackList){
        $this->blackList = $blackList;
        return $this;
    }


    public function addFilter($name,$value = ''){
        if($name)
            array_push($this->filters,["$name"=>$value]);
        return $this;
    }


    public function removeFilter($name){
        if($name){
            foreach($this->filters as $filter_name => $filter_value){
                if($filter_name == $name)
                    unset($this->filters["$filter_name"]);
            }
        }
        return $this;
    }

    /**
     * 筛选器中是否有某筛选条件的某个值
     *
     * @param string $name
     * filter name
     * @param string $value
     * filter value
     * @param mixed $trueReturn
     * @param mixed $falseReturn
     *
     * @return bool
     */
    public function isActive($name = '',$value = FilterManager::ALL,$trueReturn = true,$falseReturn = false){
        $current_filters = $this->filters;
        if(!$name || !$value)
            return false;
        if(!$current_filters || !isset($current_filters["$name"])){
            if($value == FilterManager::ALL)
                return $trueReturn;
            else
                return $falseReturn;
        }
        $arra = explode(',',$current_filters["$name"]);
        if(in_array($value,$arra)){
            return $trueReturn;
        }else{
            return $falseReturn;
        }
    }

    /**
     * get full url(with params)
     * 获取待参数的url
     *
     * @param string $name
     * filter name
     * @param string $value
     * filter value
     * @param bool $multi
     * Whether to support more value filtering,
     * if $value == FilterManager::ALL, this parameter does`t work
     * @param array $LinkageRemoveFilters
     * Linkage to remove the filter联动过滤
     * @param array $blackList
     *
     * @return string
     */
    public function url($name = '',$value = FilterManager::ALL,$multi = false ,Array $LinkageRemoveFilters = [],Array $blackList = null){
        $filters = [];
        $current_filters = $this->filters;

        if(!$name || !$value)
            return $this->bootUrl;

        if(!$current_filters || !count($current_filters))
            return  $value!=FilterManager::ALL ? "$this->bootUrl?$name=$value" : $this->bootUrl;

        if(!isset($current_filters["$name"]) && $value != FilterManager::ALL){
            if($this->isPass($name,$LinkageRemoveFilters,$blackList))
                $filters["$name"] = $value;
        }

        foreach($current_filters as $filter_name => $filter_value){
            if($this->isPass($filter_name,$LinkageRemoveFilters,$blackList)){
                if ($name == "$filter_name") {
                    if($value != FilterManager::ALL) {
                        if($multi){
                            $arra = explode(',', $filter_value);
                            if (in_array($value, $arra)) {
                                $new_arra                = array_diff($arra, [$value]);
                                $filters["$filter_name"] = implode(',', $new_arra);
                            } else {
                                array_push($arra, $value);
                                $filters["$filter_name"] = implode(',', $arra);
                            }
                        }else{
                            $filters["$filter_name"] = $value;
                        }
                    }
                } else {
                    $filters["$filter_name"] = $filter_value;
                }
            }
        }
        $url = "$this->bootUrl?";
        $params = [];
        foreach($filters as $key => $filter){
            if($filter) $params[] = "$key=$filter";
        }
        return $url . implode('&',$params);
    }


    /**
     * 是否通过 联动过滤 和 黑名单过滤
     * @param       $filter_name
     * @param array $LinkageRemoveFilters
     * @param array $blackList
     *
     * @return bool
     */
    private function isPass($filter_name,Array $LinkageRemoveFilters = [],Array $blackList = null){
        if( count($LinkageRemoveFilters) > 0 ){
            if(in_array($filter_name,$LinkageRemoveFilters))
                return false;
        }
        if( ! $blackList || count($blackList) == 0 ){
            $blackList = $this->blackList;
        }
        if(in_array($filter_name,$blackList))
            return false;
        return true;
    }

}