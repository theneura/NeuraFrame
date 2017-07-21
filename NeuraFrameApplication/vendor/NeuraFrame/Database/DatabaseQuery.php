<?php 

namespace NeuraFrame\Database;

trait DatabaseQuery
{
    /**
    * Table name
    *
    * @var string
    */
    protected $table;

    /**
    * Data Container
    *
    * @var array
    */
    protected $data = [];

    /**
    * Wheres
    *
    * @var array
    */
    protected $wheres = [];

    /**
    * Selects
    *
    * @var array
    */
    protected $selects = [];

    /**
    * Order by
    *
    * @var array
    */
    protected $orderBy = [];

    /**
    * Limit
    *
    * @var int
    */
    protected $limit;

    /**
    * Offset
    *
    * @var int
    */
    protected $offset;

    /**
    * Total Rows
    *
    * @var int
    */
    protected $rows;

    /**
    * Joins
    *
    * @var array
    */
    protected $joins = [];

    /**
    * Bindings Container
    *
    * @var array
    */
    protected $bindings = [];

    /**
    * Last Insert id
    *
    * @var int
    */
    protected $lastId;

    /**
    * Set data for updating or saving 
    *
    * @param array $data 
    */
    public function setData(array $data)
    {        
        $this->data = $data;
        return $this;
    }

    /**
    * Set select clause
    *
    * @param string $select 
    * @return this
    */
    public function select($select)
    {
        $this->selects[] = $select;
        return $this;
    }

    /**
    * Set join clause
    *
    * @param string $join 
    * @return this
    */
    public function join($join)
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
    * Set Limit and offset
    *
    * @param int $limit 
    * @param int $offset
    * @return this
    */
    public function limit($limit,$offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
    * Set Order by clause
    *
    * @param string $orderBy
    * @param string $sort
    * @return this
    */
    public function orderBy($orderBy,$sort = 'ASC')
    {
        $this->orderBy = [$orderBy,$sort];
        return $this;
    }

    /**
    * Fetch Table
    * This will return only one record
    *
    * @param string $table
    * @return \stdObject | null
    */
    public function fetch($table = null)
    {        
        $this->table($table);
        $sql = $this->fetchStatement();
        $resoult = $this->query($sql,$this->bindings)->fetch(); 
        $this->reset();  
        return $resoult ? $resoult : null;
    }

    /**
    * Fetch all Records from Table
    *
    * @param string $table
    * @return array
    */
    public function fetchAll($table = null)
    {
        $this->table($table);
        $sql = $this->fetchStatement();
        $query = $this->query($sql,$this->bindings);
        $results = $query->fetchAll();
        $this->rows = $query->rowCount();
        $this->reset();
        return $results ? $results : null;
    }

    /**
    * Get total rows from last fetch all statements
    *
    * @return int 
    */
    public function rows()
    {
        return $this->rows;
    }

    /**
    * Prepare Select Statement
    *
    * @return string
    */
    protected function fetchStatement()
    {
        $sql = 'SELECT';
        if($this->selects){
            $sql .= implode(',',$this->selects);
        }else{
            $sql .= ' *';
        }
        $sql .= ' FROM ' . $this->table;

        if($this->joins){
            $sql .= implode(' ',$this->joins);
        }

        if($this->wheres){
            $sql .= ' WHERE '.implode(' AND ',$this->wheres);
        }        
        if($this->offset){
            $sql .= ' OFFSET '.$this->offset;
        }
        if($this->orderBy){
            $sql .= ' ORDER BY ' . implode(' ',$this->orderBy);
        }
        if($this->limit){
            $sql .= ' LIMIT '.$this->limit;
        }
        return $sql;
    }

    /**
    * Set the table name
    *
    * @param string $table 
    * @return $this 
    */
    public function table($table = null)
    {
        if($table)
            $this->table = $table;  
        return $this;
    }

    /**
    * Set the data that will be stored in database table
    *
    * @param string $table 
    * @return $this 
    */
    public function from($table)
    {
        return $this->table($table);
    }

    /**
    * Set the data that will be stored in database table
    *
    * @param mixed $key
    * @param mixed $value
    * @return $this 
    */
    public function data($key,$value = null)
    {
        if(is_array($key)){
            $this->data = array_merge($this->data,$key);
            $this->addToBindings($key);
        }else{
            $this->data[$key] = $value;
            $this->addToBindings($value);
        }
        return $this;
    }

    /**
    * Inser data to database
    *
    * @param string $table
    * @return this
    */
    public function insert($table= null)
    { 
        $this->table($table);
        $sql = 'INSERT INTO '. $this->table;
        if(!empty($this->data))
        {
            $sql .= ' SET ';
            $sql .= $this->setFields();
        }else{
            $sql .= ' VALUES ()';
        }
        $this->query($sql,$this->bindings);
        $this->lastId = $this->connection()->lastInsertId();
        $this->reset();
        return $this;
    }

    /**
    * Set the fields for inser and update
    *
    * @return string
    */
    protected function setFields()
    {
        $sql = '';
        foreach(array_keys($this->data) as $key => $value){
            if($this->data[$value])
                $sql .= ''. $value . ' = "'.$this->data[$value].'" , ';
        }
        $sql = rtrim($sql,', ');
        return $sql;
    }

    /**
    * Update data in database
    *
    * @param string $table
    * @return this
    */
    public function update($table= null)
    {
        $this->table($table);
        $sql = 'UPDATE '. $this->table;  
        $sql .= ' SET ';
        $sql .= $this->setFields();

        $sql = rtrim($sql,', ');
        if($this->wheres){
            $sql .= ' WHERE '.implode(' ',$this->wheres);
        }
        $this->query($sql,$this->bindings);
        $this->reset();
        return $this;
    }

    /**
    * Delete Clause
    *
    * @param string $table
    * @return this
    */
    public function delete($table= null)
    {
        $this->table($table);
        $sql = 'DELETE FROM '. $this->table . ' ';     
        $sql .= $this->setFields();

        $sql = rtrim($sql,', ');
        if($this->wheres){
            $sql .= ' WHERE '.implode(' ',$this->wheres);
        }
        $this->query($sql,$this->bindings);
        $this->reset();
        return $this;
    }

    /**
    * Execute the given sql statemant
    *
    * @return \PDOStatemant
    */
    public function query()
    {       
        $bindings = func_get_args();
        $sql = array_shift($bindings);
        if(count($bindings) == 1 AND is_array($bindings[0])){
            $bindings = $bindings[0];
        }
        
        try{
            $query = $this->connection()->prepare($sql);
            foreach($bindings as $key => $value){
                $query->bindValue($key + 1,_e($value));
            }
            $query->execute(); 
            return $query;
        }catch(PDOException $e){
            pre($sql);
            pre($this->bindings);
            die($e->getMessage());
        }
    }

    /**
    * Add new where clause
    *
    * @return $this 
    */
    public function where()
    {
        $bindings = func_get_args();
        $sql = array_shift($bindings);
        $this->addToBindings($bindings);
        $this->wheres[] = $sql;
        return $this;
    }

    /**
    * Check if table column exists
    *
    * @param string $table 
    * @param string $column
    * @return bool
    */
    public function keyExist($table,$column)
    {
        $this->table($table);
        $query = $this->query("SHOW COLUMNS FROM `".$table."` LIKE '".$column."'");
        $this->reset();
        return $query->rowCount()?true:false;
        
    }

    /**
    * Add the given value to bindings
    *
    * @param mixed $value 
    * @return void
    */
    protected function addToBindings($value)
    {
        if(is_array($value)){
            $this->bindings = array_merge($this->bindings,array_values($value));
        }else{
            $this->bindings[] = $value;
        }        
    }

    /**
    * Reset all data
    * 
    * @return void
    */
    protected function reset()
    {
        $this->rows = 0;
        $this->limit = null;
        $this->offset = null;
        $this->table = null;        
        $this->data = [];
        $this->selects = [];
        $this->joins = [];
        $this->wheres = [];        
        $this->bindings = [];
        $this->orderBy = [];
    }

    /**
    * Get the last insert id
    *
    * @return int
    */
    public function lastId()
    {
        return $this->lastId;
    }
}