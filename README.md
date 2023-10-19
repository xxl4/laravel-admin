Requirements
------------
 - PHP >= 7.2.5
 - Laravel >= 5.5.0
 - Fileinfo PHP Extension

Installation
------------

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require nicelizhi/laravel-admin
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="Nicelizhi\Admin\AdminServiceProvider"
```
After run command you can find config file in `config/admin.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install.
```
php artisan admin:install
```

Open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

Configurations
------------
The file `config/admin.php` contains an array of configurations, you can find the default configurations in there.

Right to left support
------------
just go to this path `<YOUR_PROJECT_PATH>\vendor\Nicelizhi\laravel-admin\src\Traits\HasAssets.php` and modify `$baseCss` array for loading right to left (rtl) version of bootstap and AdminLTE css files.    
**bootstrap.min.css** change it to **bootstrap.rtl.min.css**    
**AdminLTE.min.css** change it to **AdminLTE.rtl.min.css**  

Bug Report
------------
[https://github.com/nicelizhi/laravel-admin/issues](https://github.com/nicelizhi/laravel-admin/issues)

## Extensions
[laravel admin products](https://github.com/nicelizhi/laravel-admin-products)  
[laravel admin orders](https://github.com/nicelizhi/laravel-admin-orders)  
[laravel admin category](https://github.com/nicelizhi/laravel-admin-category)  
[laravel admin taobao](https://github.com/nicelizhi/laravel-admin-taobao)  
[laravel admin douyin](https://github.com/nicelizhi/laravel-admin-douyin)  
[laravel admin JD](https://github.com/nicelizhi/laravel-admin-jd)  
[laravel admin Baidu](https://github.com/nicelizhi/laravel-admin-baidu)  
[laravel admin Amazon](https://github.com/nicelizhi/laravel-admin-amazon)  
[config](https://github.com/nicelizhi/laravel-admin-config)  
[message](https://github.com/nicelizhi/laravel-admin-message)  
[Amazon Books](https://github.com/nicelizhi/amazon-books)  

Other
------------
`laravel-admin` based on following plugins or services:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://adminlte.io/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)
+ [Nestable](http://dbushell.github.io/Nestable/)
+ [toastr](http://codeseven.github.io/toastr/)
+ [X-editable](http://github.com/vitalets/x-editable)
+ [bootstrap-number-input](https://github.com/wpic/bootstrap-number-input)
+ [fontawesome-iconpicker](https://github.com/itsjavi/fontawesome-iconpicker)
+ [sweetalert2](https://github.com/sweetalert2/sweetalert2)

License
------------
`laravel-admin` is licensed under [The MIT License (MIT)](LICENSE).
