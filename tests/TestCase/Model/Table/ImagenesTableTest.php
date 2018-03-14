<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImagenesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImagenesTable Test Case
 */
class ImagenesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.imagenes',
        'app.articulos',
        'app.categorias',
        'app.rsses',
        'app.portales',
        'app.imagenes_articulos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Imagenes') ? [] : ['className' => 'App\Model\Table\ImagenesTable'];
        $this->Imagenes = TableRegistry::get('Imagenes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Imagenes);

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
