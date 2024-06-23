<?php
class DbController
{

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * init the object with a \PDO object
     * @param type $pdo
     */
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    /**
     * create tables 
     */
    public function createTables(){
        $sqlList = ['CREATE TABLE IF NOT EXISTS users (
                        id serial PRIMARY KEY,
                        username character varying(255) NOT NULL UNIQUE,
                        email character varying(255) NOT NULL UNIQUE 
                     );'
        ];

        $sqlList = [
            'CREATE TABLE IF NOT EXISTS users (
                id serial PRIMARY KEY,
                username character varying(255) NOT NULL UNIQUE,
                email character varying(255) NOT NULL UNIQUE,
                password character varying(255) NOT NULL,
                fullName character varying(255) NOT NULL
            );',
            'CREATE TABLE IF NOT EXISTS emails (
                id serial PRIMARY KEY,
                userId character varying(255) NOT NULL,
                recipient character varying(255) NOT NULL,
                subject character varying(255) NOT NULL,
                body text NOT NULL
            );'
        ];

        // execute each sql statement to create new tables
        foreach ($sqlList as $sql) {
            $this->pdo->exec($sql);
        }

        return $this;
    }

    /**
     * return tables in the database
     */
    public function getTables(){
        $stmt = $this->pdo->query("SELECT table_name 
                                   FROM information_schema.tables 
                                   WHERE table_schema= 'public' 
                                        AND table_type='BASE TABLE'
                                   ORDER BY table_name");
        $tableList = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tableList[] = $row['table_name'];
        }

        return $tableList;
    }

    public function dropTables(){
        $sql ='drop table users, emails;';
        $this->pdo->exec($sql);

        return $this;
    }
}