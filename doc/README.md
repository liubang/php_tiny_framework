### 模板引擎

框架中提供了两种模板引擎机制，一种是使用原生php，另一种是使用类似于smarty的模板标签.

使用原生php作为模板引擎，只需要修改配置文件

```php
return [
//...
    VIEW_DRIVER => 'simple',
//...
];
```

使用内置模板引擎

```php
return [
//...
    VIEW_DRIVER => 'linger',
//...
];
```

#### 模板标签

`import`

```
<import type="js" file="xxxx.js" />
<import type="css" file="xxxx.css" />

```

`for`
```
//默认步长为1
<for start="1" end="10">
    xxx
</for>
//显示指定步长
<for start="1" end="10" step="2">
    xxx
</for>
```

`foreach`
```
// foreach($a as $v) 
<foreach name="a" item="v">
    xxx
</foreach>

// foreach($a as $k => $v)
<foreach name="a" $key="k" item="v">
    xxx
</foreach>
```

`include`
```
// include "xxxx";
<include file="xxxx" />
```

`if`
```
<if condition="$a > 1">
    xxx
</if>
```

`elseif`
```
<if condition="$a > 1">
    xxx
<elseif condition="$a < -10" />
    xxx
</if>
```

`else`
```
<if condition="$a > 1">
    xxx
<else />
    xxx
</if>
```

`empty`
```
// if (empty($a)) 
<empty condition="$a">
    xxx
</empty>
```