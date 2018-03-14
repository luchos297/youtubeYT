<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemasDiaPalabraClaveTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemasDiaPalabraClaveTable Test Case
 */
class TemasDiaPalabraClaveTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TemasDiaPalabraClaveTable
     */
    public $TemasDiaPalabraClave;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.temas_dia_palabra_clave',
        'app.palabra_claves'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TemasDiaPalabraClave') ? [] : ['className' => 'App\Model\Table\TemasDiaPalabraClaveTable'];
        $this->TemasDiaPalabraClave = TableRegistry::get('TemasDiaPalabraClave', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TemasDiaPalabraClave);

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
