<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BannerTiposTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BannerTiposTable Test Case
 */
class BannerTiposTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BannerTiposTable
     */
    public $BannerTipos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.banner_tipos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BannerTipos') ? [] : ['className' => 'App\Model\Table\BannerTiposTable'];
        $this->BannerTipos = TableRegistry::get('BannerTipos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BannerTipos);

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
}
