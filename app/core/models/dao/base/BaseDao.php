<?php
namespace app\core\models\dao\base;

class BaseDao{
    protected $connection;
    protected $table;


    public function __construct(?\PDO $connection, string $table){
        $this->connection = $connection;
        $this->table = $table;
    }


    // metodos

    public function foundRows():int{
        $stmt = $this->connection->query("SELECT FOUND_ROWS();");
        $data = $stmt->fecth(\PDO::FETCH_NUM);
        $stmt->closeCursor();
        return (int) $data[0];
    }

    public function getLastInsertId(): int{
        $id = $this->connection->lastInsertId();
        return is_numeric($id) ? (int) $id:0;
    }


}