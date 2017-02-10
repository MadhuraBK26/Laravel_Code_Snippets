<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class AppServiceProvider extends ServiceProvider
{

    /**
     * @var App\Services\Auth\AclAuthentication
     */
    private $aclAuthentication;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->aclAuthentication = app("Acl");
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('hasRole', function($paramsValue) {
             return " <?php \$hasRole = \$acl->is".$paramsValue."; if(\$hasRole): ?> ";
        });
         
        Blade::directive('endHasRole', function() {           
             return " <?php endif; ?> ";
        });
        
        Blade::directive('hasPermission', function($paramsValue) {
             return " <?php \$hasPermission = \$acl->can".$paramsValue."; if(\$hasPermission): ?> ";
        });
        
        Blade::directive('endHasPermission', function() {           
             return " <?php endif; ?> ";
        });
        
        Blade::directive('canCrud', function($paramsValue = null) {
             return "<?php \$permissionGroupSlug = isset(\$permissionGroupSlug) ? \$permissionGroupSlug : null; \$canCrud = \$acl->canCrud".$paramsValue."; ?> <?php if(\$canCrud): ?> ";
        });
        
        Blade::directive('endCanCrud', function() {           
             return " <?php endif; ?> ";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }

}
