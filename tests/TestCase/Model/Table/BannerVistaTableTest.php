<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BannerVistaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BannerVistaTable Test Case
 */
class BannerVistaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BannerVistaTable
     */
    public $BannerVista;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.banner_vista',
        'app.banners',
        'app.banner_tipos',
        'app.views',
        'app.banner_view',
        'app.vistas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BannerVista') ? [] : ['className' => 'App\Model\Table\BannerVistaTable'];
        $this->BannerVista = TableRegistry::get('BannerVista', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BannerVista);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
