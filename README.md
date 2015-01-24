# FilterManage for Laravel
Filter manager package for product list,elegant generate url.
(产品列表筛选管理器，让你优雅的获得筛选器链接)

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

# Example
 You can find most of the usage in the this file->demo_temp_for_laravel.balde.php
 
 Filter manager already importing filters(request data) by Service Provider,
 so,make sure your request params submit by get or post method,the import request params(filters data) code is:
 ```php
    $this->app['FilterManager'] = $this->app->share(function(){
                return FilterManager::create(\Input::all())->setBlackList(['page']);
            });
 ```
 Commonly used method： 
 1.create a intall of FilterManager,it`s a static method:
 $filters:this is filters data ,exp:['gender'=>'male','city'=>'beijing']
 $baseUrl:default null
 $blackList:this is blacklist for filtrs,exp:['pageindex'] 
 ```php
    FilterManager::create(Array $filters,$baseUrl = "",Array $blackList = array());
 ```
 2.add a Filter
 addFilter($filter_name,$filter_value)
 ```php
    FilterManager::addFilter('gender','male');
 ```
 3.remove a filter
 removeFilter($filter_name)
 ```php
    FilterManager::removeFilter('gender');
 ```
 4.has filter,return value or false
 has($filter_name)
 
 5.is active
 isActive($filter_name)
 
 6.get url(one filter has some value,and every value has a url)
 url($filter_name,$filter_value,$multi,$LinkageRemoveFilters,$blackList)
 $filter_name:filter name,required.
 $filter_value:one value of the filter,defult all value :\Toplan\FilterManager\FilterManager::ALL.
 $multi:the filter is support multi value?false or true,default false.
 $LinkageRemoveFilters：linkage remove the other filter,default null.
 $blackList:temporary blacklist ,default null.
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
 
