<?php
class crud
{
	public $insert = "insert into ";
	public $select = "select ";
	public $update = "update ";
	public $delete = "delete from ";

	function __construct($host, $user, $pass, $db)
	{
		$conn = mysqli_connect($host, $user, $pass, $db);
		return $this->conn = $conn;
	}
	public function masukkan($table, $data = array())
	{
		$end = end($data);
		$this->insert .= "$table (";
		foreach($data as $d => $v)
		{
			$this->insert .= $d;
			if ($v != $end)
				$this->insert .= ",";
		}
		$this->insert .= ") values (";
		
		foreach($data as $d => $v)
		{
			$this->insert .= "'$v'";
			if ($v != $end)
				$this->insert .= ",";
		}
		$this->insert .= ")";
		return $this;
	}
	//$math = max(), min(), avg(), sum(), dll
	function tampil($field, $table, $math=false)
	{
		if ($math != false)
			$this->select .= $math."(".$field.")";
		else
			$this->select .= $field;
		$this->select .= " from " .$table." ";
		$this->table = $table;
		return $this;
	}

	public function dimana($dml, $opt = array(), $like) 
	{
		$query = "";
		if($dml=="ubah")
			$query .= $this->update;
		if($dml=="tampil")
			$query .= $this->select;
		if($dml=="hapus")
			$query .= $this->delete;
		
			foreach ($opt as $key => $value) 
			{
				$key = $key;
				$val = $value;
			}
			$totKey = count($opt);
			if($totKey == 1) 
			{
				if($like == "" or $like == "=") 
					$query .= " WHERE $key = '$val'";
				else if($like == "like")
					$query .= " WHERE $key LIKE '%$val%'";
				else
					$query .= " WHERE $key ".$like." '".$val."'";
			}

			else 
			{
				$query .= " WHERE ";
				$i = 0;
				foreach ($opt as $key => $value) 
				{
					if($i++ == count($opt) - 1) 
						$and = "";
					else
						$and = "and";
					if($like == "") 
						$query .= $key . " = '" . $value. "' ".$and." ";
					else
						$query .= $key . " LIKE '%" . $value. "%' ".$and." ";
				}
			}
		if($dml=="ubah")
			$this->update = $query;
		if($dml=="tampil")
			$this->select = $query;
		if($dml=="hapus")
			$this->delete = $query;
		return $this;
	}

	public function berdasarkan($field)
	{
		$this->select .= "order by '".$field."' ";
		return $this;
	}
	public function having($field, $operator, $value)
	{
		$this->select .= "having (".$field.") ".$operator." ".$value;
		return $this;
	}

	public function join($position, $tabel, $field, $field2)
	{
		$this->select .= " ".$position." join ".$tabel." on ".$this->table.".".$field." = ".$tabel.".".$field2;
		return $this;
	}

	public function ubah($table, $param = array())
	{
		$end = end($param);
		$this->update .= " ".$table." set ";
		foreach ($param as $key => $value) 
		{
			$this->update .= $key." = '".$value."' ";
			if ($value != $end)
				$this->update .= ", ";
		}
		return $this;
	}

	public function hapus($table)
	{
		$this->delete .= " ".$table." ";
		return $this;
	}

	public function eksekusi($query)
	{
		if($query=="masukkan")
			$q = mysqli_query($this->conn, $this->insert);
		else if($query=="tampil")
			$q = mysqli_query($this->conn, $this->select);
		else if($query=="ubah")
			$q = mysqli_query($this->conn, $this->update);
		else if($query=="hapus")
			$q = mysqli_query($this->conn, $this->delete);
		return $q;
	}
}
