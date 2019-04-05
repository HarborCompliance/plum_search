<?php
/**
 * PlumSearch plugin for CakePHP Rapid Development Framework
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Evgeny Tomenko
 * @since         PlumSearch 0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace PlumSearch\Test\TestCase\Controller;

use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase;

/**
 * PlumSearch\Controller\AutocompleteTrait Test Case
 */
class AutocompleteTraitTest extends IntegrationTestCase
{
    /**
     * Test fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.plum_search.articles',
        'plugin.plum_search.tags',
        'plugin.plum_search.articles_tags',
        'plugin.plum_search.authors',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Router::reload();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Controller);
        parent::tearDown();
    }

    /**
     * Test autocomplete method
     *
     * @return void
     */
    public function testAutocompleteSuccess()
    {
        $this->useHttpServer(true);
        $this->get('/ExtArticles/autocomplete?query=r&parameter=author_id');
        $response = json_decode($this->_response->getBody(), true);
        $this->assertEquals('success', $response['status']);
        $this->assertEquals([
            ['id' => 2, 'value' => 'mark'],
            ['id' => 3, 'value' => 'larry'],
        ], $response['data']);
    }

    /**
     * Test autocomplete method
     *
     * @return void
     */
    public function testAutocompleteFail()
    {
        $this->useHttpServer(true);
        $this->get('/ExtArticles/autocomplete?query=%');
        $response = json_decode($this->_response->getBody(), true);
        $this->assertEquals([], $response['data']);
        $this->assertEquals('error', $response['status']);
        $this->assertEquals('Field not found', $response['message']);
    }
}
