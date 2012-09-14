<?php

class TableSlugifier {
    
    /** @var Slugifier */
    private $slugifier;
    
    /** @var PDO */
    private $connection;
    
    public function __construct($connection) {
        $this->slugifier = new Slugifier();
        $this->connection = $connection;
    }
    
    public function slugifyTable($table, array $columns, $slugColumn='slug') {
        $sql = 'SELECT * FROM ' . $table . ' t WHERE t.' . $slugColumn . ' IS NULL';
        $select = $this->connection->prepare($sql);
        $select->execute();
        $updateSql = 'UPDATE ' . $table . ' SET ' . $slugColumn . ' = :slug WHERE id = :id';
        $update = $this->connection->prepare($updateSql);
        $findSql = 'SELECT COUNT(*) as count FROM ' . $table . ' WHERE ' . $slugColumn . ' = :slug';
        $find = $this->connection->prepare($findSql);
        while (($record = $select->fetch()) !== false) {
            $values = array();
            foreach ($columns as $column) {
                $value = $record[$column];
                if ($value !== null) {
                    $values[] = $value;
                }
            }
            $addedId = false;
            $addedNumber = false;
            if (count($values) == 0) {
                $values[] = $record['id'];
                $addedId = true;
            }
            $counter = 2;
            $skip = false;
            do {
                $slug = $this->slugifier->slugify(implode('-', $values));
                if (strlen($slug) == 0) {
                    $skip = true;
                    break;
                }
                else {
                    $find->bindValue('slug', $slug);
                    $find->execute();
                    $findResult = $find->fetch();
                    $exists = $findResult['count'] > 0;
                }
                if ($exists) {
                    if (!$addedId) {
                        $values[] = $record['id'];
                        $addedId = true;
                    }
                    else {
                        if ($addedNumber) {
                            $values[count($values)-1] = $counter++;
                        }
                        else {
                            $values[] = $counter++;
                        }
                        $addedNumber = true;
                    }
                }
            } while ($exists);
            if ($skip) continue;
            $update->execute(array('slug' => $slug, 'id' => $record['id']));
        }
    }
    
}