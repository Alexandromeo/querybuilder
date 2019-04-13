<?php

class back_index
{
	public $insert = "insert into ";
	public $select = "select ";
	public $update = "update ";
	public $delete = "delete from ";

	function __construct()
	{
		$conn = new mysqli("localhost","root","","qbuilder");
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

		$this->conn->query($this->insert);
		print_r($this->insert);
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

	//$not, $and dan $value2 khusus untuk between. $not = not between, $and = between a and b, $value = b
	public function dimana($dml, $pre, $field, $logic, $value, $and=false, $value2=false, $not=false)
	{
		$query = "";
		if ($dml == "tampil")
			$query .= $this->select; 
		else if ($dml == "ubah")
			$query .= $this->update;
		else if($dml == "hapus")
			$query .= $this->delete;

		if ($logic != "antara")
		{
			$and = false;
			$value2 = false;
		}

		if ($pre == "dimana")
			$pre = "where";
		else if($pre == "dan")
			$pre = "and";
		else if($pre == "atau")
			$pre = "or";

		$query .= $pre." ".$field." ";

		//not
		if ($not == "bukan")
			$not = "not";
 
		//operator
		if ($logic == "sama")
			$logic = "==";
		else if ($logic == "bukan")
			$logic = "!=";
		else if ($logic == "antara")
			$logic = "between";

		//like
		if ($logic == "seperti")
			$query .= "like %".$value."%";
		else if($logic == "seperti_depan")
			$query .= "like %".$value;
		else if($logic == "seperti_belakang")
			$query .= "like ".$value."%";
		else
			$query .= $not." ".$logic." '".$value."' ";

		if ($and == "dan")
		{	
			$and = "and";
			$query .= $and." '".$value2."'";
		}

		if ($dml == "tampil")
			$this->select = $query;
		else if($dml == "ubah")
			$this->update = $query;
		else if($dml == "hapus")
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

	public function selesai($end)
	{
		return $this->$end;
	}
}