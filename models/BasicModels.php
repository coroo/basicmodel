<?php if( !defined('BASEPATH')) exit('No direct script access alloed');

class BasicModels extends CI_Model
{
	/*
		## function getRecordCount() ##
		# Goal : 
			1) return number of rows
		# How to call:
			$this->BasicModels->getRecordCount('tbl_name',$condition_array);
		# Parameters :
		    * => indicates parameter is must
			1) $tbl_name*   = name of table 
			2) $condition	= array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
	*/
	public function getRecordCount($tbl_name,$condition=FALSE)
	{
		if($condition!="" && count($condition)>0)
		{
			foreach($condition as $key=>$val)
			{ $this->db->where($key,$val); }
        }
        $this->db->where('deletedAt',null);
		$num=$this->db->count_all_results($tbl_name);
		return $num;
	}
	
	/*
		## function getRecords() ##
		# Goal : 
            1) return array of records from table
            *without softDelete
		# How to call:
			$this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name*   = name of table 
			2) $condition   = array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
			3) $select      = ('col1,col2,col3');
			4) $order_by    = array('colname1'=>order,'colname2'=>order); Order='ASC OR DESC'
			5) $start       = start for paging (number)
			6) $limit       = limit for paging (number)
            7) $join        = array('jointable'=>$table_b,'match_a'=>$table_a_reference,'match_b'=>$table_b_reference,'join_type'=>$join_type); join_type = FALSE/'LEFT'/'RIGHT'
            *In case where we need joins, you can pass joins in controller also.
            Ex: 
                $this->db->join('tbl_nameB AS b','tbl_nameA.col=b.col','left');
                $this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);			
	*/
	public function getRecords($tbl_name,$condition=FALSE,$select=FALSE,$order_by=FALSE,$start=FALSE,$limit=FALSE,$join=FALSE)
	{
		if($select!="")
		{$this->db->select($select);}

		if(count($join)>0 && $join!="")
		{
			$a = $tbl_name; 			//table 1
			$b = $join['jointable']; 	//table 2
			$this->db->join($b, $a.'.'.$join['match_a'].' = '.$b.'.'.$join['match_b'],$join['join_type']);
		}
		
        $this->db->where('deletedAt',null);
        
		if(count($condition)>0 && $condition!="")
		{ $condition=$condition; }
		else
		{$condition=array();}
		if(count($order_by)>0 && $order_by!="")
		{
			foreach($order_by as $key=>$val)
			{$this->db->order_by($key,$val);}
		}
		if($limit!="" || $start!="")
		{ $this->db->limit($limit,$start);}
		
        $rst=$this->db->get_where($tbl_name,$condition);
		return $rst->result_array();
    }
	
	/*
		## function getSoftDeleteRecords() ##
		# Goal : 
            1) return array of softDelete records from table
		# How to call:
			$this->BasicModels->getSoftDeleteRecords('tbl_name',$condition_array,$select,...);
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name*   = name of table 
			2) $condition   = array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
			3) $select      = ('col1,col2,col3');
			4) $order_by    = array('colname1'=>order,'colname2'=>order); Order='ASC OR DESC'
			5) $start       = start for paging (number)
			6) $limit       = limit for paging (number)
            7) $join        = array('jointable'=>$table_b,'match_a'=>$table_a_reference,'match_b'=>$table_b_reference,'join_type'=>$join_type); join_type = FALSE/'LEFT'/'RIGHT'
            *In case where we need joins, you can pass joins in controller also.
            Ex: 
                $this->db->join('tbl_nameB AS b','tbl_nameA.col=b.col','left');
                $this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);			
	*/
	public function getSoftDeletedRecords($tbl_name,$condition=FALSE,$select=FALSE,$order_by=FALSE,$start=FALSE,$limit=FALSE,$join=FALSE)
	{
		if($select!="")
		{$this->db->select($select);}

		if(count($join)>0 && $join!="")
		{
			$a = $tbl_name; 			//table 1
			$b = $join['jointable']; 	//table 2
			$this->db->join($b, $a.'.'.$join['match_a'].' = '.$b.'.'.$join['match_b'],$join['join_type']);
		}
		
        $this->db->where('deletedAt is NOT NULL', NULL, FALSE);
        
		if(count($condition)>0 && $condition!="")
		{ $condition=$condition; }
		else
		{$condition=array();}
		if(count($order_by)>0 && $order_by!="")
		{
			foreach($order_by as $key=>$val)
			{$this->db->order_by($key,$val);}
		}
		if($limit!="" || $start!="")
		{ $this->db->limit($limit,$start);}
		
        $rst=$this->db->get_where($tbl_name,$condition);
		return $rst->result_array();
    }
    
	/*
		## function insertRecord() ##
		# Goal : 
			1) insert record, on successful updates return success: true.
		# How to call:
			$this->BasicModels->insertRecord('tbl_name',$data_array,$id);
		# Parameters :
		    * => indicates parameter is must
			1) $tbl_name*   = name of table 
			2) $data_array* = array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
			3) $id = primary column value. only use insert_id if ID is autoincrement;
	*/
	public function insertRecord($session,$tbl_name,$data_array,$insert_id=FALSE)
	{
        $data_array['createdAt'] = date("Y-m-d H:i:s");
		if($this->db->insert($tbl_name,$data_array))
		{
            $db_insert_id = $this->db->insert_id();
            $this->load->helper('db_helper');
            // log($this->db->last_query(), $session['user_id']);
			if($insert_id==true)
            {return $db_insert_id;}
			else
			{return array('success' => true);}
		}
		else
		{return array('success' => false, 'error' => $this->db->error());}
	}
	
	/*
		## function updateRecord($tbl_name,$data_array,$pri_col,$id) ##
		# Goal : 
			1) updates record, on successful updates return success: true.
		# How to call:
			$this->BasicModels->updateRecord('tbl_name',$data_array,$pri_col,$id)
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name* = name of table 
			2) $data_array* = array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
			3) $pri_col* = primary key or column name depending on which update query need to fire.
			4) $id* = primary column or condition column value.
	*/
	public function updateRecord($tbl_name,$data_array,$where_arr)
	{
		$this->db->where($where_arr,NULL);
		if($this->db->update($tbl_name,$data_array))
		{return array('success' => true);}
		else
		{return array('success' => false, 'error' => $this->db->error());}
	}
	
	/*
		# function softDeleteRecord($tbl_name,$pri_col,$id)
		# Goal : 
			1) Instead delete record from table, we add deletedAt in database
		# How to call:
			$this->BasicModels->softDeleteRecord('tbl_name','pri_col',$id)
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name* = name of table 
			2) $pri_col* = primary key or column name depending on which update query need to fire.
			3) $id* = primary column or condition column value.
		# It will useful while deleting record from single table. *delete join will not work here.
	*/
	public function softDeleteRecord($session,$tbl_name,$pri_col,$id)
	{
        $data_array['deletedAt'] = date("Y-m-d H:i:s");
		$this->db->where($pri_col,$id);
		if($this->db->update($tbl_name,$data_array))
		{
            $this->load->helper('db_helper');
            // log($this->db->last_query(), $session['user_id']);
            return array('success' => true);
        }
		else
		{return array('success' => false, 'error' => $this->db->error());}
	}
	
	/*
		# function restoreSoftDeletedRecord($tbl_name,$pri_col,$id)
		# Goal : 
			1) restore data which already in softDeletedRecord
		# How to call:
			$this->BasicModels->restoreSoftDeletedRecord('tbl_name','pri_col',$id)
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name* = name of table 
			2) $pri_col* = primary key or column name depending on which update query need to fire.
			3) $id* = primary column or condition column value.
		# It will useful while deleting record from single table. *delete join will not work here.
	*/
	public function restoreSoftDeletedRecord($session,$tbl_name,$pri_col,$id)
	{
        $data_array['deletedAt'] = NULL;
		$this->db->where($pri_col,$id);
		if($this->db->update($tbl_name,$data_array))
		{
            $this->load->helper('db_helper');
            // log($this->db->last_query(), $session['user_id']);
            return array('success' => true);
        }
		else
		{return array('success' => false, 'error' => $this->db->error());}
	}
	
	/*
		# function hardDeleteRecord($tbl_name,$pri_col,$id)
		# Goal : 
			1) delete record from table, and store it to log
		# How to call:
			$this->BasicModels->hardDeleteRecord('tbl_name','pri_col',$id)
		# Parameters : 
		    * => indicates parameter is must
			1) $tbl_name* = name of table 
			2) $pri_col* = primary key or column name depending on which update query need to fire.
			3) $id* = primary column or condition column value.
		# It will useful while deleting record from single table. *delete join will not work here.
	*/
	public function hardDeleteRecord($session,$tbl_name,$pri_col,$id)
	{
		$this->db->where($pri_col,$id);
		if($this->db->delete($tbl_name))
		{
            $this->load->helper('db_helper');
            // log($this->db->last_query(), $session['user_id']);
            return $this->db->last_query();
        }
		else
		{return array('success' => false, 'error' => $this->db->error());}
	}

}
?>