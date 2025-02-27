<?php

namespace GenesisTecnologia\Defender\Testing;

use Illuminate\Support\Facades\Blade;

/**
 * Class DefenderServiceProviderTest.
 */
class DefenderServiceProviderTest extends AbstractTestCase
{
    /**
     * Array of service providers.
     * @var array
     */
    protected $providers = [
        'GenesisTecnologia\Defender\Providers\DefenderServiceProvider',
    ];

    /**
     * TestCases that should not register the service provider.
     * @var array
     */
    protected $skipProvidersFor = [
        'testShouldNotCompileDefenderTemplateHelpers',
        'testShouldNotLoadHelpers',
    ];

    public function testModelBindings()
    {
        $this->assertInstanceOf('GenesisTecnologia\Defender\Role', $this->app->make('GenesisTecnologia\Defender\Contracts\Role'));

        $this->assertInstanceOf('GenesisTecnologia\Defender\Permission', $this->app->make('GenesisTecnologia\Defender\Contracts\Permission'));
    }

    /**
     * Verify if all services are in service container.
     */
    public function testContainerShouldBeProvided()
    {
        $contracts = [
            [
                'interface' => 'GenesisTecnologia\Defender\Contracts\Defender',
                'implementation' => 'GenesisTecnologia\Defender\Defender',
                'alias' => 'defender',
            ],
            [
                'interface' => 'GenesisTecnologia\Defender\Contracts\Javascript',
                'implementation' => 'GenesisTecnologia\Defender\Javascript',
                'alias' => 'defender.javascript',
            ],
            [
                'interface' => 'GenesisTecnologia\Defender\Contracts\Repositories\PermissionRepository',
                'implementation' => 'GenesisTecnologia\Defender\Repositories\Eloquent\EloquentPermissionRepository',
                'alias' => 'defender.permission',
            ],
            [
                'interface' => 'GenesisTecnologia\Defender\Contracts\Repositories\RoleRepository',
                'implementation' => 'GenesisTecnologia\Defender\Repositories\Eloquent\EloquentRoleRepository',
                'alias' => 'defender.role',
            ],
        ];

        foreach ($contracts as $contract) {
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['interface']]);
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['implementation']]);
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['alias']]);
            $this->assertInstanceOf($contract['implementation'], $this->app[$contract['alias']]);
        }
    }

    /**
     * Verify if blade is rendering defender directives.
     */
    public function testShouldCompileDefenderTemplateHelpers()
    {
        $view = $this->stubsPath('views/defender.blade.txt');
        $expected = $this->stubsPath('views/defender.blade.output.txt');

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertStringNotContainsString('@shield', $compiled);
        $this->assertStringNotContainsString('@is', $compiled);
        $this->assertStringNotContainsString('@endshield', $compiled);
        $this->assertStringNotContainsString('@endis', $compiled);
        $this->assertStringNotContainsString('@isnot', $compiled);
        $this->assertStringNotContainsString('@endisnot', $compiled);
        $this->assertStringEqualsFile($expected, $compiled);
    }

    /**
     * If configuration is disabled, template helpers will not be available.
     * Note: The service provider should not be register before that test.
     */
    public function testShouldNotCompileDefenderTemplateHelpers()
    {
        $this->app['config']->set('defender.template_helpers', false);

        $this->app->register('GenesisTecnologia\Defender\Providers\DefenderServiceProvider');

        $view = $this->stubsPath('views/defender.blade.txt');
        $expected = $this->stubsPath('views/defender.blade.output.txt');

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertStringContainsString('@shield', $compiled);
        $this->assertStringContainsString('@is', $compiled);
        $this->assertStringContainsString('@endshield', $compiled);
        $this->assertStringContainsString('@endis', $compiled);
        $this->assertStringContainsString('@isnot', $compiled);
        $this->assertStringContainsString('@endisnot', $compiled);

        $this->assertStringNotEqualsFile($expected, $compiled);
    }

    /**
     * Verify if the Defender function helpers are loaded.
     */
    public function testShouldLoadHelpers()
    {
        $this->assertTrue(function_exists('defender'), 'Helper \'defender()\' not loaded.');
        $this->assertTrue(function_exists('hasPermission'), 'Helper \'hasPermission()\'  not loaded.');
        $this->assertTrue(function_exists('roles'), 'Helper \'roles()\'  not loaded.');
    }

    /**
     * Verify if the Defender function helpers are loaded.
     * Note: The service provider should not be register before that test.
     * Note:That test needs to be runned in isolation. Because it depends of helpers.php
     * (file with functions which are always loaded).
     */
    public function testShouldNotLoadHelpers()
    {
        $this->assertFalse(isset($this->app['defender']));

        $this->app['config']->set('defender.helpers', false);

        $this->app->register('GenesisTecnologia\Defender\Providers\DefenderServiceProvider');

        if ($this->isInIsolation()) {
            $this->assertFalse(function_exists('defender'), 'Helper \'defender()\' loaded.');
            $this->assertFalse(function_exists('hasPermission'), 'Helper \'hasPermission()\'  loaded.');
            $this->assertFalse(function_exists('roles'), 'Helper \'roles()\'  loaded.');
        }
    }

    /**
     * Publishes the configuration and migrations.
     */
    public function testShouldPublishConfigAndMigrations()
    {
        $this->markTestSkipped('remake this test');

        return

        $this->artisan('vendor:publish');

        $resourcesPath = __DIR__.'/../../src/resources';

        $migrations = [
            $resourcesPath.'/migrations/2015_02_23_161101_create_defender_roles_table.php',
            $resourcesPath.'/migrations/2015_02_23_161102_create_defender_permissions_table.php',
            $resourcesPath.'/migrations/2015_02_23_161103_create_defender_role_user_table.php',
            $resourcesPath.'/migrations/2015_02_23_161104_create_defender_permission_user_table.php',
            $resourcesPath.'/migrations/2015_02_23_161105_create_defender_permission_role_table.php',
        ];

        /*
         * Being sure the number of migrations described is the total expected.
         */
        $this->assertEquals(
            count(glob($resourcesPath.'/migrations/*.php')),
            count(array_unique($migrations))
        );

        $config = $resourcesPath.'/config/defender.php';

        foreach ($migrations as $migration) {
            $this->assertFileExists($migration);

            $this->assertFileExists(base_path('database/migrations/'.basename($migration)));
        }

        $this->assertFileExists(config_path(basename($config)));
    }
}
