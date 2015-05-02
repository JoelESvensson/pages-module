<?php namespace Anomaly\PagesModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Filesystem\Filesystem;

/**
 * Class PagesModuleServiceProvider
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\PagesModule
 */
class PagesModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'admin/pages/fields'                => 'Anomaly\PagesModule\Http\Controller\Admin\FieldsController@index',
        'admin/pages/fields/create'         => 'Anomaly\PagesModule\Http\Controller\Admin\FieldsController@create',
        'admin/pages/fields/edit/{id}'      => 'Anomaly\PagesModule\Http\Controller\Admin\FieldsController@edit',
        'admin/pages/settings'              => 'Anomaly\PagesModule\Http\Controller\Admin\SettingsController@index',
        'admin/pages/edit/{id}'             => 'Anomaly\PagesModule\Http\Controller\Admin\PagesController@edit',
        'admin/pages/types'                 => 'Anomaly\PagesModule\Http\Controller\Admin\PageTypesController@index',
        'admin/pages/types/create'          => 'Anomaly\PagesModule\Http\Controller\Admin\PageTypesController@create',
        'admin/pages/types/edit/{id}'       => 'Anomaly\PagesModule\Http\Controller\Admin\PageTypesController@edit',
        'admin/pages/types/fields/{id}'     => 'Anomaly\PagesModule\Http\Controller\Admin\PageTypesController@fields',
        'admin/pages/types/fields/add/{id}' => 'Anomaly\PagesModule\Http\Controller\Admin\PageTypesController@add',
        'admin/pages/types/choose'          => 'Anomaly\PagesModule\Http\Controller\Ajax\TypesController@choose',
        'admin/pages/create'                => 'Anomaly\PagesModule\Http\Controller\Admin\PagesController@create',
        'admin/pages/{path?}/create'        => 'Anomaly\PagesModule\Http\Controller\Admin\PagesController@create',
        'admin/pages/{path?}'               => 'Anomaly\PagesModule\Http\Controller\Admin\PagesController@index',
    ];

    /**
     * The class bindings.
     *
     * @var array
     */
    protected $bindings = [
        'Anomaly\Streams\Platform\Model\Pages\PagesPagesEntryModel'     => 'Anomaly\PagesModule\Page\PageModel',
        'Anomaly\Streams\Platform\Model\Pages\PagesPageTypesEntryModel' => 'Anomaly\PagesModule\Type\TypeModel'
    ];

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
        'Anomaly\PagesModule\Page\Contract\PageRepositoryInterface'     => 'Anomaly\PagesModule\Page\PageRepository',
        'Anomaly\PagesModule\Type\Contract\PageTypeRepositoryInterface' => 'Anomaly\PagesModule\Type\PageTypeRepository'
    ];

    /**
     * The addon route constraints.
     *
     * @var array
     */
    protected $constraints = [
        'admin/pages/{path?}'        => [
            'path' => '(.*)'
        ],
        'admin/pages/{path?}/create' => [
            'path' => '(.*)'
        ]
    ];

    /**
     * Map additional routes.
     *
     * @param Filesystem  $files
     * @param Application $application
     */
    public function map(Filesystem $files, Application $application)
    {
        // Include public routes.
        if ($files->exists($routes = $application->getStoragePath('pages/routes.php'))) {
            $files->requireOnce($routes);
        }
    }
}
