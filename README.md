<p align="center">
  <img src="https://i.ibb.co/djnLFxG/ux-design.png" height="80" /><br/>
  <span><b>BasicModel</b>: <span>Universal Model Codeigniter [BETA]</span></a>
</p>
  
<br/>

## Why

BasicModel helps you (developer) to create a code more fast and efficient. By using BasicModel, you just need to create a controller without create any model again.

<p align="center">
  <img width="600" src="https://i.ibb.co/LxZKS63/Basic-Model.png">
</p>


## Features

- [x] **getRecordCount**: Return number of rows;
- [x] **getRecords**: Return array of records from table; *without softDelete;
- [x] **insertRecord**: Insert record, on successful updates return success: true;
- [x] **updateRecord**: Updates record, on successful updates return success: true;

- [x] **softDeleteRecord**: Instead delete record from table, we add deletedAt in database;
- [x] **getSoftDeletedRecords**: Return array of softDelete records from table;
- [x] **restoreSoftDeletedRecord**: Restore data which already in softDeletedRecord;

- [x] **hardDeleteRecord**: Delete record from table, and store it to log;

### Next features:

- [ ] **Upload File**: Upload file and record file description to database with return success;
- [ ] **Create Image Thumbnail**: Create thumb of uploaded image.;

> Which one do you want first? Any other recommendations? Search for [existing feature requests](https://github.com/coroo/basicmodel/issues) and add a 👍 reaction on them, or create a new one.

<br/>

## Contributing

Bug reports, feature requests and other contributions are more than welcome! <br/>
Whenever possible, please make a pull request with the implementation instead of just requesting it.

> If the feature is big, please open an issue first for discussion.
<br/>

## Running it locally

- `cd application/models`
- `git clone git@github.com:devhubapp/devhub.git`

That's it. It will copy the basicModel to your model. 

## Database Schema

For purpose of soft delete vs hard delete, you need to create your table looks like this:

```
┌────┬─────┬───────────┬───────────┬───────────┐
│ id │ ... │ createdAt │ updatedAt │ deletedAt │
├────┼─────┼───────────┼───────────┼───────────┤
│  1 │ ... │           │           │           │
├────┼─────┼───────────┼───────────┼───────────┤
│  2 │ ... │           │           │           │
└────┴─────┴───────────┴───────────┴───────────┘

- `createdAt`   | DateTime | Use to capture time data stored in database
- `updatedAt`   | DateTime | CURRENT_TIMESTAMP | Use to capture time data updated
- `deletedAt`   | DateTime | Use to capture time data softdeleted
```

<br/>

## How to Call

## `getRecordCount`

***Goal :***
  * return number of rows
  
***How to call:***
```php
  $this->BasicModels->getRecordCount('tbl_name',$condition_array);
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table 
- `$condition `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);

<br/>

## `getRecords`

***Goal :***
  * return array of records from table (without softDelete)
  
***How to call:***
```php
  $this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table 
- `$condition `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$select `: ('col1,col2,col3');
- `$order_by `: array('colname1'=>order,'colname2'=>order); Order='ASC OR DESC';
- `$start `: start for paging (number);
- `$limit `: limit for paging (number);
- `$join `: array('jointable'=>$table_b,'match_a'=>$table_a_reference,'match_b'=>$table_b_reference,'join_type'=>$join_type); join_type = FALSE/'LEFT'/'RIGHT';

> Note: in case where we need joins, you can pass joins in controller also:
```php
  $this->db->join('tbl_nameB AS b','tbl_nameA.col=b.col','left');
  $this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);	
```

<br/>

## `getSoftDeleteRecords`

***Goal :***
  * return array of softDelete records from table
  
***How to call:***
```php
  $this->BasicModels->getSoftDeleteRecords('tbl_name',$condition_array,$select,...);
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table 
- `$condition `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$select `: ('col1,col2,col3');
- `$order_by `: array('colname1'=>order,'colname2'=>order); Order='ASC OR DESC';
- `$start `: start for paging (number);
- `$limit `: limit for paging (number);
- `$join `: array('jointable'=>$table_b,'match_a'=>$table_a_reference,'match_b'=>$table_b_reference,'join_type'=>$join_type); join_type = FALSE/'LEFT'/'RIGHT';

> Note: in case where we need joins, you can pass joins in controller also:
```php
  $this->db->join('tbl_nameB AS b','tbl_nameA.col=b.col','left');
  $this->BasicModels->getRecords('tbl_name',$condition_array,$select,...);	
```

<br/>

## `insertRecord`

***Goal :***
  * insert record, on successful updates return success: true.
  
***How to call:***
```php
  $this->BasicModels->insertRecord('tbl_name',$data_array,$id);
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table 
- `$data_array++ `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$id `: primary column value. only use insert_id if ID is autoincrement;

<br/>

## `updateRecord`

***Goal :***
  * updates record, on successful updates return success: true.
  
***How to call:***
```php
  $this->BasicModels->updateRecord('tbl_name',$data_array,$pri_col,$id)
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table;
- `$data_array++ `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$pri_col++ `: primary key or column name depending on which update query need to fire;
- `$id `: primary column value. only use insert_id if ID is autoincrement;

<br/>

## `softDeleteRecord`

***Goal :***
  * instead delete record from table, we add deletedAt in database
  
***How to call:***
```php
  $this->BasicModels->softDeleteRecord('tbl_name','pri_col',$id)
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table;
- `$pri_col++ `: primary key or column name depending on which update query need to fire;
- `$id `: primary column or condition column value;

> It will useful while deleting record from single table. *delete join will not work here.

<br/>

## `restoreSoftDeletedRecord`

***Goal :***
  * restore data which already in softDeletedRecord.
  
***How to call:***
```php
  $this->BasicModels->restoreSoftDeletedRecord('tbl_name','pri_col',$id)
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table;
- `$data_array++ `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$pri_col++ `: primary key or column name depending on which update query need to fire;
- `$id `: primary column or condition column value;

> It will useful while deleting record from single table. *delete join will not work here.

<br/>

## `hardDeleteRecord`

***Goal :***
  * delete record from table, and store it to log.
  
***How to call:***
```php
  $this->BasicModels->hardDeleteRecord('tbl_name','pri_col',$id)
```
***Parameters :***
> ++ indicates parameter is must

- `$tbl_name++ `: name of table;
- `$data_array++ `: array('column_name1'=>$column_val1,'column_name2'=>$column_val2);
- `$pri_col++ `: primary key or column name depending on which update query need to fire;
- `$id `: primary column or condition column value;

> It will useful while deleting record from single table. *delete join will not work here.

<br/>

## Author

Copyright (c) 2018 [Coroowicaksono](https://coroowicaksono.com).
