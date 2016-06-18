<?php
namespace Lare_Team\Lare;
use \PHPUnit_Framework_TestCase;

include('src/PHP_Lare/Lare.php');

class TestView {
    static private $id = "";
    static private $name = "";
    static private $start_time;

    public function __construct($id = '', $name = '')
    {
        self::$start_time = microtime(true);

        self::$id = $id;
        self::$name = $name;

        // Lare
        Lare::set_current_namespace('Lare.'.$id);
    }
}

class LareTest extends \PHPUnit_Framework_TestCase
{
    public function testNamespaceValid()
    {
        $a = new TestView('page1', 'Test');
        $needle = '`Lare.page1`';
        $this->assertRegexp(preg_quote($needle), Lare::get_current_namespace());
    }

    public function testNamespaceReplace()
    {
        $a = new TestView('page1', 'Test');
        Lare::set_current_namespace('Lare.page2');
        $needle = '`Lare.page2`';
        $this->assertRegexp(preg_quote($needle), Lare::get_current_namespace());
    }

    public function testPage1LareMatching1()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.Page2';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertEquals(1, Lare::get_matching_count());
    }

    public function testPage1LareMatching2()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertEquals(2, Lare::get_matching_count());
    }

    public function testPage1LareMatches1()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertTrue(Lare::matches('Lare'));
    }

    public function testPage1LareMatches2()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test.test1';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertTrue(Lare::matches('Lare.test'));
    }

    public function testPage1LareNotMatches()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test1.test1';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertFalse(Lare::matches('Lare.test'));
    }

    public function testPage1LareIsEnabled()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test';
        $_SERVER['HTTP_X_LARE_VERSION'] = '1.0.0';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertTrue(Lare::is_enabled());
    }

    public function testPage1LareIsNotEnabled()
    {
        $_SERVER['HTTP_X_LARE'] = '';
        $_SERVER['HTTP_X_LARE_VERSION'] = '';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertFalse(Lare::is_enabled());
    }

    public function testPage1LareOutdatedVersion()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test';
        $_SERVER['HTTP_X_LARE_VERSION'] = '0.0.1';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertFalse(Lare::is_enabled());
    }

    public function testPage1LareFutureVersion()
    {
        $_SERVER['HTTP_X_LARE'] = 'Lare.test';
        $_SERVER['HTTP_X_LARE_VERSION'] = '10.0.4';
        Lare::get_instance(true);
        $a = new TestView('test', 'Test');
        $this->assertTrue(Lare::is_enabled());
    }
}
