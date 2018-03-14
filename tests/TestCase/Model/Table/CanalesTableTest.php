<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CanalesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CanalesTable Test Case
 */
class CanalesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CanalesTable
     */
    public $Canales;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.canales'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Canales') ? [] : ['className' => 'App\Model\Table\CanalesTable'];
        $this->Canales = TableRegistry::get('Canales', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Canales);

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
