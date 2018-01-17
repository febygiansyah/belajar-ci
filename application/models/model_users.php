<?php

class Model_users extends CI_Model
{
	public $table = 'users';
	
	public function cek_akun($username,$password)
	{
		//get data user_error
		$this->db->where('username',$username);
		$this->db->where('active','1');
		
		//query
		$query = $this->db->get($this->table)->row();
		if(!$query) return false;
		
		//data password dari database
		$hash = $query->password;
		
		//jika hash tidak sama dengan password maka return false
		if(!password_verify($password,$hash)) return false;
		
		//update last_login user
		$last_login = $this->update($query->id, array('last_login' => date('Y-m-d H:i:s')));
		
		return $query;
	}
	
	public function cekPasswordLama($id, $password)
	{
		//get data user
		$this->db->where('id',$id);
		$this->db->where('active','1');
		
		//run query
		$query = $this->db->get($this->table)->row();
		
		//jika query gagal maka return false
		if (!$query) return false;
		
		//ambil data password dari db
		$hash = $query->password;
		
		if (!password_verify($password, $hash)) return false;
		
		return $query;
	}
	
	
	public function get()
	{
		$query = $this->db->get($this->table);
		return $query;
	}
	
	public function get_where($where)
	{
		$query = $this->db
		->where($where)
		->get($this->table);
		
		return $query;
	}
	
	public function insert($data)
	{
		$query = $this->db->insert($this->table, $data);
		
		return $query;
	}
	
	public function update($id, $data)
	{
		$query = $this->db
		->where('id',$id)
		->update($this->table, $data);
		
		return $query;
	}
	
	public function delete($id)
	{
		$query = $this->db
		->where('id', $id)
		->delete($this->table);
		
		return $query;
	}
}