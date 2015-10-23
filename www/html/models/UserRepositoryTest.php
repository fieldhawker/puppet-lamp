<?php

class UserRepositoryTest extends PHPUnit_Extensions_Database_TestCase
{
    static private $pdo  = null;
    private        $conn = null;
    private        $obj;

    public function __construct()
    {

        $data_set = $this->createXMLDataSet(dirname(__FILE__) . '/../data/import.xml');

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($data_set);
        $this->getDatabaseTester()->onSetUp($data_set);

        $this->obj = new UserRepository(self::$pdo);
    }

    /**
     * @return null|PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    final public function getConnection()
    {

        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                  $GLOBALS['DB_DSN'],
                  $GLOBALS['DB_USER'],
                  $GLOBALS['DB_PASSWD']);
            }
            $this->conn
              = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_ArrayDataSet
     */
    public function getDataSet()
    {
        $data = array();

        return $this->createArrayDataSet($data);
    }

    /**
     *
     */
    public function testUserValidInsert()
    {

        // temp
//        $this->assertEquals(2, $this->getConnection()->getRowCount('user'));
//        $this->assertTableRowCount("user", "2");

        $params = array('email' => '', 'password' => 'password');
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'aaaaaaaaa', 'password' => 'password');
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'a@a.d', 'password' => 'password');
        $res    = $this->obj->validInsert($params);
//        var_dump($res);
        $this->assertFalse(empty($res));

        $params = array(
          'email'    => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@se-project.co.jp',
          'password' => 'password'
        );
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => 'password');
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => '');
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => 'a');
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array(
          'email'    => 'takano2@se-project.co.jp',
          'password' => '1234567890123456789012345678901234567890'
        );
        $res    = $this->obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => 'password');
        $res    = $this->obj->validInsert($params);
//        var_dump($res);
        $this->assertTrue(empty($res));

    }


    /**
     *
     */
    public function testUserValidAuth()
    {

        $params = array();
        $res    = $this->obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => '', 'password' => 'password');
        $res    = $this->obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'aaaaaaaaa', 'password' => 'password');
        $res    = $this->obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => '');
        $res    = $this->obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => 'password');
        $res    = $this->obj->validAuth($params);
        $this->assertTrue(empty($res));

    }
}