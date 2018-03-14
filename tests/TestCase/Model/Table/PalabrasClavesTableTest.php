<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PalabrasClavesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PalabrasClavesTable Test Case
 */
class PalabrasClavesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
//        'app.palabras_claves',
        'app.articulos',
        'app.categorias',
        'app.portales',
        'app.imagenes',
        'app.articulo_imagen',
        'app.articulo_palabra_clave',
//        'app.articulos_palabras_claves'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PalabrasClaves') ? [] : ['className' => 'App\Model\Table\PalabrasClavesTable'];
        $this->PalabrasClaves = TableRegistry::get('PalabrasClaves', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PalabrasClaves);

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
