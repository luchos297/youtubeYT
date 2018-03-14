<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VistasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VistasTable Test Case
 */
class VistasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VistasTable
     */
    public $Vistas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vistas',
        'app.banner_vista',
        'app.banners',
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
        $config = TableRegistry::exists('Vistas') ? [] : ['className' => 'App\Model\Table\VistasTable'];
        $this->Vistas = TableRegistry::get('Vistas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vistas);

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
