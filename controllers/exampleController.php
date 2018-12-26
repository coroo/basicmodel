<?php
/*
    #use BasicModel
    #use REST

    index_get   -> used for get/read data
    index_post  -> used to post data
    index_put   -> used to update/edit data

    table: `your_table_name`
*/

class exampleController extends REST_Controller {

    public function __construct($config = 'rest') 
    {
        parent::__construct($config);
        $this->load->model('BasicModels');
    }

    public function get_get() 
    {
        $listdata = $this->BasicModels->getRecords('your_table_name');
        $this->response($listdata, 200);
    }

    public function insert_post() 
    {
        $session = array('user_id'=> $this->post('user_id'));
        $dataInsert = array(
            'column_id'    => $this->post('column_id'),
            'column_name'    => $this->post('column_name')
        );
        $insert = $this->BasicModels->insertRecord($session,'your_table_name',$dataInsert);
        if ($insert) {
            $this->response($insert, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    public function update_post() 
    {
        $dataUpdate = array(
            'column_id'    => $this->post('column_id'),
            'column_name'    => $this->post('column_name')
        );
        $where = " column_id='".$this->post('column_id')."'";
        $update = $this->BasicModels->updateRecord('your_table_name',$dataUpdate,$where);
        if ($update) {
            $this->response($update, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    public function delete_post() 
    {
        $session = array('user_id'=> $this->post('user_id'));
        $deleteId = $this->post('column_id');
        $where = " column_id='".$this->post('column_id')."'";
        $delete = $this->BasicModels->softDeleteRecord($session,'your_table_name','column_id',$deleteId);
        if ($delete) {
            $this->response($delete, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    public function readsoftdeleted_get() 
    {
        $listdata = $this->BasicModels->getSoftDeletedRecords('your_table_name');
        $this->response($listdata, 200);
    }

    public function updatesoftdeleted_post() 
    {
        $session = array('user_id'=> $this->post('user_id'));
        $deleteId = $this->post('column_id');
        $where = " column_id='".$this->post('column_id')."'";
        $delete = $this->BasicModels->restoreSoftDeletedRecord($session,'your_table_name','column_id',$deleteId);
        if ($delete) {
            $this->response($delete, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

}