# FilterManager for Laravel
Filter manager package for product list,let`s elegant generate filter url.

# Installation

```php
{
  "require": {
    // ...
    "toplan/filter-manager": "dev-master",
   }
}
```

# Usage

To use the FilterManager Service Provider, you must register the provider when bootstrapping your Laravel application. There are essentially two ways to do this.

Find the providers key in app/config/app.php and register the HTMLPurifier Service Provider.
```php
    'providers' => array(
        // ... 
        'Toplan\FilterManager\FilterManagerServiceProvider',
    )
```    
Find the aliases key in app/config/app.php.
```php
    'aliases' => array(
        // ...
        'FilterManager' => 'Toplan\FilterManager\Facades\FilterManager',
    )
```

# Instruction
 Filter manager has imported filters (request data) by service provider. So,make sure your request params was submited by get or post method. Code in service provider:
 ```php
    $this->app['FilterManager'] = $this->app->share(function(){
                return FilterManager::create(\Input::all())->setBlackList(['page']);
            });
 ```
# Commonly used method 
 You can find most of the usage in the this file->demo_temp_for_laravel.balde.php
 
 * create a instance of FilterManager.
 ### create($filters,$baseUrl,$blackList);
 
 $filters: this is filters data ,required,exp:['gender'=>'male','city'=>'beijing']
 
 $baseUrl: default=array().
 
 $blackList: this is blacklist for filtrs,default=array(),exp:['pageindex'].
 
 * set black list for filter
 ### setBlackList($filter_name_array)
 ```php
  FilterManager::setBlackList(['page']);
 ```

 * has filter,return value or false
  ### has($filter_name)
 ```php
    FilterManager::has('gender');
 ```
 
 * is active
 ### isActive($filter_name)
 ```php
    FilterManager::isActive('gender','male');#this will return ture or false;
    FilterManager::isActive('gender','male','active','not active');#this will return 'active' or 'not active';
 ```
 
 * get url(one filter has some values,and every value has url)
 ### url($filter_name,$filter_value,$multi,$LinkageRemoveFilters,$blackList)

 $filter_name: filter name,required.
 
 $filter_value: one value of the filter, defult=\Toplan\FilterManager\FilterManager::ALL.
 
 $multi: whether to support multiple? false or true, default=false.
 
 $LinkageRemoveFilters：linkage remove the other filter, default=array().
 
 $blackList: temporary blacklist, default=array().
 
 exp:
 ```html
  <li class="item all {{FilterManager::isActive('gender',\Toplan\FilterManager\FilterManager::ALL,'active','')}}">
    <a href="{{FilterManager::url('gender',\Toplan\FilterManager\FilterManager::ALL)}}">All</a>
  </li>
  <li class="item @if(FilterManager::isActive('gender','male')) active @endif">
    <a href="{{FilterManager::url('gender','male')}}">Male</a>
  </li>
  <li class="item @if(FilterManager::isActive('gender','female')) active @endif">
    <a href="{{FilterManager::url('gender','female')}}">Female</a>
  </li>
 ```
 
