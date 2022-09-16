<?php

//////////////Single Table Edit Commmon Functions///////////////////////

function show_manage_single_table_button($tname,$label='')
{
	if(strlen($label)==0){$label=$tname;}
	echo '<div class="d-inline-block" ><form method=post class=print_hide>
	<button class="btn btn-outline-primary btn-sm" name=tname value=\''.$tname.'\' >'.$label.'</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=action value=manage_single_table>
	</form></div>';
}

function single_table_button_with_action($tname,$label='',$action)
{
	if(strlen($label)==0){$label=$tname;}
	echo '<div class="d-inline-block" ><form method=post class=print_hide>
	<button class="btn btn-outline-primary btn-sm" name=tname value=\''.$tname.'\' >'.$label.'</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=action value=\''.$action.'\'>
	</form></div>';
}

function show_crud_button($tname,$type,$label='')
{
	if(strlen($label)==0){$label=$type;}
	echo '<div class="d-inline-block" ><form method=post class=print_hide>
	<button class="btn btn-outline-primary btn-sm" name=action value=\''.$type.'\' >'.$label.'</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=tname value=\''.$tname.'\'>
	</form></div>';
}

function add($link,$tname)
{
	run_query($link,$GLOBALS['database'],'insert into `'.$tname.'` () values()');
	$id=last_autoincrement_insert($link);
	//edit($link,$tname,$id,$header='yes');
	echo '<table class="table table-striped table-sm table-bordered">';
		view_row($link,$tname,$id,'yes');
	echo '</table>';
}

function add_without_display($link,$tname)
{
	run_query($link,$GLOBALS['database'],'insert into `'.$tname.'` () values()');
	return $id=last_autoincrement_insert($link);
}

function view_row($link,$tname,$pk,$header='no')
{
	$sql='select * FROM `'.$tname.'` where id=\''.$pk.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	
	if($header=='yes')
	{
		echo '<tr>';
		foreach($ar as $k=>$v)
		{
			echo '<td>'.$k.'</td>';
		}
		echo '</tr>';
	}
	
	echo '<tr>';
	foreach($ar as $k =>$v)
	{
		if($k=='id')
		{
			echo '
			<td>';
				ste_id_edit_button($link,$tname,$v);
				ste_id_delete_button($link,$tname,$v);
			echo '<span class="round round-0 bg-warning" >'.$v.'</span></td>';
		}
		elseif(substr(get_field_type($link,$tname,$k),-4)=='blob')
		{
			echo '<td>';
				ste_view_field_blob($link,$tname,$k,$pk);
			echo '</td>';
		}
		else
		{
			echo '<td>';
			$fspec=get_field_spec($link,$tname,$k);
			if($fspec!==null)
			{
				if($fspec['ftype']=='dtable')
				{
					$sql='select 
					distinct `'.$fspec['field'].'` , 
						concat_ws("|",'.$fspec['field_description'].') as description
					from `'.$fspec['table'].'` where id=\''.$v.'\'';
					//echo $sql;
					$result=run_query($link,$GLOBALS['database'],$sql);
					$ar=get_single_row($result);
					if($ar!==null)
					{
						//mk_select_from_sql_with_description($link,$sql,
						//	$fspec['field'],$fspec['fname'],$fspec['fname'],'',$v,$blank='yes');
						echo '<pre>'.$ar['description'].'('.htmlentities($v).')</pre></td>';
					}
					else
					{
						echo '<pre>('.htmlentities($v).')</pre></td>';
					}
				}
				else
				{
					echo '<pre>'.htmlentities($v).'</pre></td>';
				}
			}
			else
			{
				echo '<pre>'.htmlentities($v).'</pre></td>';
			}
		}
	}
	echo '</tr>';
}

function get_field_type($link,$tname,$fname)
{
	$ar=get_field_details($link,$tname,$fname);
	return $ar['Type'];
}

function get_field_details($link,$tname,$fname)
{
	$sql='show columns from `'.$tname.'` where Field=\''.$fname.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	return $ar=get_single_row($result);	
}

function ste_view_field_blob($link,$tname,$fname,$id)
{
		$sql_blob='select `'.$fname.'` from `'.$tname.'` where id=\''.$id.'\' ';
		$result_blob=run_query($link,$GLOBALS['database'],$sql_blob);
		$ar_blob=get_single_row($result_blob);
		
		echo '<div>';
			ste_echo_download_button($link,$tname,$fname,$id);
		echo '</div>';
				
}

function ste_echo_download_button($link,$tname,$fname,$id)
{
	echo '<form method=post action=ste_download.php class="d-inline" >
			<input type=hidden name=table value=\''.$tname.'\'>
			<input type=hidden name=field value=\''.$fname.'\' >
			<input type=hidden name=primary_key value=\''.$id.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			
			<button class="btn btn-info  btn-sm"  
			formtarget=_blank
			type=submit
			name=action
			value=download>Download</button>
		</form>';
}

function search($link,$tname)
{
	$sql='show columns from `'.$tname.'`';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$all_fields=array();
	while($ar=get_single_row($result))
	{	
		$all_fields[]=$ar;
	}
	
	echo '<form method=post>';
	echo '<table class="table table-striped table-sm table-bordered">';
	echo '<tr><td>Action</td>';
	foreach($all_fields as $field)
	{
		echo '<td>'.$field['Field'].'</td>';
	}
	echo '<td>Action</td>';
	echo '</tr>';
	
	echo '<tr>';
	
	echo '<td><button class="btn btn-info  btn-sm"  
		type=submit
		name=action
		value=and_select>and Search</button>';
	echo '<button class="btn btn-info  btn-sm"  
		type=submit
		name=action
		value=or_select>or Search</button></td>';
		
	foreach($all_fields as $field)
	{
		if(substr($field['Type'],-4)=='blob')
		{
			echo '<td>Blob</td>';
		}
		else
		{	
			echo '<td>';		
				//'yes' to ensure date dropdown is not displayed
				read_field($link,$tname,$field['Field'],'','yes');
				//echo '<td><input type=text name=\''.$field['Field'].'\'></td>';
			echo '</td>';		

		}
	}
	

	echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
	echo '<input type=hidden name=tname value=\''.$tname.'\'>';

	echo '</tr>';
			
	echo '</table>';
	echo '</form>';
}


function view_sql_result_as_table($link,$sql,$show_hide='yes')
{
	if(!$result=run_query($link,$GLOBALS['database'],$sql))
	{
		 echo '<h1>Problem</h1>';
		 return false;
	}
	display_sql_result_data($result,$show_hide);
}

	
function display_sql_result_data($result,$show_hide='yes')
{
	echo '<div>';	
	
	if($show_hide=='yes')
	{
		echo '<button data-toggle="collapse" data-target="#sql_result" class="btn btn-dark">Show/Hide Result</button>';
		echo '<div id="sql_result" class="collapse show">';		
	}
	else
	{
		echo '<div>';	
	}
	
       echo '<table border=1 width=100% class="table-striped table-hover">';
					
			$first_data='yes';

			while($array=get_single_row($result))
			{
			//echo '<pre>';
			//print_r($array);
                if($first_data=='yes')
                {
                        echo '<tr bgcolor=lightgreen>';
                        foreach($array as $key=>$value)
                        {
                                echo '<th>'.$key.'</th>';
                        }
                        echo '</tr>';
                        $first_data='no';
                }
                echo '<tr>';
                foreach($array as $k=>$v)
                {
                        echo '<td>'.$v.'</td>';    
                }
                echo '</tr>';
        }
        echo '</table>';	
		echo '</div>';
	echo '</div>';	
	
}
//111119500892
//one

function select_with_condition($link,$tname,$join='and',$condition,$order_str='')
{
	//echo '<pre>';print_r($_POST);echo '</pre>';	
	$sql='select id from `'.$tname.'` where ';
	$w='';
	$ord=' order by ';
	$ord_base_len=strlen($ord);
	foreach($condition  as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name')))
		{
			if(strlen($v)>0)
			{
    			$w=$w.' `'.$k.'` like \'%'.$v.'%\' '.$join.' ';
				$ord=$ord.' `'.$k.'` , ';
			}
		}
	}
	
	if(strlen($ord)>$ord_base_len){$ord=substr($ord,0,-2);}
	else{$ord='';}
	
	if(strlen($w)>0)
	{
		if($join=='and')
		{
			$w=substr($w,0,-4);
		}
		if($join=='or')
		{
			$w=substr($w,0,-3);
		}
		$sql=$sql.$w.$order_str;
	}
	else
	{
		//$sql='select id from `'.$tname.'` order by id desc limit '.$GLOBALS['all_records_limit'];
		$sql='select id from `'.$tname.'` '.$ord.' limit '.$GLOBALS['all_records_limit'];
	}
	
	//echo $sql;
	
	$result=run_query($link,$GLOBALS['database'],$sql);
	$all_fields=array();
	$header='yes';
	echo '<table class="table table-striped table-sm table-bordered">';
	while($ar=get_single_row($result))
	{	
		view_row($link,$tname,$ar['id'],$header);
		$header='no';
	}		
	echo '</table>';
}

function select($link,$tname,$join='and',$order_by='')
{
	//echo '<pre>';print_r($_POST);echo '</pre>';	
	$sql='select id from `'.$tname.'` where ';
	$w='';
	foreach($_POST  as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name')))
		{
			if(strlen($v)>0)
			{
    			$w=$w.' `'.$k.'` like \'%'.$v.'%\' '.$join.' ';
			}
		}
	}
	
	if(strlen($w)>0)
	{
		if($join=='and')
		{
			$w=substr($w,0,-4);
		}
		if($join=='or')
		{
			$w=substr($w,0,-3);
		}
		$sql=$sql.$w.' '.$order_by;
	}
	else
	{
		//$sql='select id from `'.$tname.'` order by id desc limit '.$GLOBALS['all_records_limit'];
		$sql='select id from `'.$tname.'` limit '.$GLOBALS['all_records_limit'];
	}
	
	//echo $sql;
	
	$result=run_query($link,$GLOBALS['database'],$sql);
	$all_fields=array();
	$header='yes';
	echo '<table class="table table-striped table-sm table-bordered">';
	while($ar=get_single_row($result))
	{	
		view_row($link,$tname,$ar['id'],$header);
		$header='no';
	}		
	echo '</table>';
}

function ste_id_edit_button($link,$tname,$id)
{
	echo 
	'<div class="d-inline-block" >
		<form method=post>
			<button class="btn btn-outline-success btn-sm m-0 p-0" name=id value=\''.$id.'\' >
				<img class="m-0 p-0" src=img/edit.png alt=E width="25" height="25">
			</button>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			<input type=hidden name=action value=edit>
			<input type=hidden name=tname value=\''.$tname.'\'>
		</form>
	</div>';
}


function ste_id_delete_button($link,$tname,$id)
{
	echo 
	'<div class="d-inline-block" >
		<form method=post>
			<button class="btn btn-outline-success btn-sm m-0 p-0" 
				onclick="return confirm(\'R U Sure to delete ??\')"
				name=id value=\''.$id.'\' >
				<img class="m-0 p-0" src=img/delete.png alt=X width="25" height="25">
			</button>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			<input type=hidden name=action value=delete>
			<input type=hidden name=tname value=\''.$tname.'\'>
		</form>
	</div>';
}

function ste_id_update_button($link,$tname,$id)
{
	echo 
	'<div class="d-inline-block" >
			<button class="btn btn-outline-success btn-sm m-0 p-0" name=id value=\''.$id.'\' ><h5>Save('.$id.')</h5></button>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			<input type=hidden name=action value=update>
			<input type=hidden name=tname value=\''.$tname.'\'>
	</div>';
}

function edit_with_readonly($link,$tname,$pk,$header='no',$readonly_array=array())
{
	$sql='select * FROM `'.$tname.'` where id=\''.$pk.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
	echo '<div class="two_column_one_by_two bg-light">';
			foreach($ar as $k =>$v)
			{
				if($k=='id')
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						ste_id_update_button($link,$tname,$v);
					echo '</div>';
				}
				elseif(substr(get_field_type($link,$tname,$k),-4)=='blob')
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						echo '<input type=file name=\''.$k.'\' >';
					echo '</div>';
				}
				elseif(in_array($k,array('recording_time','recorded_by')))
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						echo $v;
					echo '</div>';
				}
				elseif(in_array($k,$readonly_array))
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
					echo '<input class="w-100" type=text  readonly name=\''.$k.'\' value=\''.htmlentities($v,ENT_QUOTES).'\'>';
					echo '</div>';
				}
				else
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						read_field($link,$tname,$k,$v);
					echo '</div>';
				}
			}
			echo '</div>';
	echo'</form>';

}



function edit($link,$tname,$pk,$header='no')
{
	$sql='select * FROM `'.$tname.'` where id=\''.$pk.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
	echo '<div class="two_column_one_by_two bg-light">';
			foreach($ar as $k =>$v)
			{
				if(substr(get_field_type($link,$tname,$k),-4)=='blob')
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						echo '<input type=file name=\''.$k.'\' >';
					echo '</div>';
				}
				elseif(in_array($k,array('recording_time','recorded_by','id')))
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						echo $v;
					echo '</div>';
				}
				else
				{
					echo '<div class="border">'.$k.'</div>';
					echo '<div class="border">';
						read_field($link,$tname,$k,$v);
					echo '</div>';
				}


			}
			echo '<div class="border">id</div>';
			echo '<div class="border">';
				ste_id_update_button($link,$tname,$ar['id']);
			echo '</div>';
			echo '</div>';
	echo'</form>';

}


function add_direct($link,$tname,$header='no')
{
	$sql='show columns from `'.$tname.'` ';
	$result=run_query($link,$GLOBALS['database'],$sql);
		
	
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
	echo '<div class="two_column_one_by_two bg-light">';
			while ( $ar=get_single_row($result))
			{
				//print_r($ar);
				//Array ( [Field] => id [Type] => int(11) [Null] => NO [Key] => PRI [Default] => [Extra] => auto_increment ) 
				
				if($ar['Field']=='id')
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo 'auto';
					echo '</div>';
				}
				elseif(substr($ar['Type'],-4)=='blob')
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo '<input type=file name=\''.$ar['Field'].'\' >';
					echo '</div>';
				}
				elseif(in_array($ar['Field'],array('recording_time','recorded_by')))
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo 'auto';
					echo '</div>';
				}
				else
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						read_field($link,$tname,$ar['Field'],'');
					echo '</div>';
				}
				
			}

			echo '</div>';

			echo 
				'<div class="d-block" >
					<button type=submit class="btn btn-block btn-outline-success btn-sm m-0 p-0" name=action value=save_insert ><h5>Save</h5></button>
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					<input type=hidden name=tname value=\''.$tname.'\'>
				</div>';
	echo'</form>';
}

function add_direct_with_default($link,$tname,$header='no',$default=array(),$readonly=array())
{
	//print_r($readonly);
	
	$sql='show columns from `'.$tname.'` ';
	$result=run_query($link,$GLOBALS['database'],$sql);
		
	echo '<h2>Insert new entry</h2>';
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
	echo '<div class="two_column_one_by_two bg-light">';
			while ( $ar=get_single_row($result))
			{
				//print_r($ar);
				//Array ( [Field] => id [Type] => int(11) [Null] => NO [Key] => PRI [Default] => [Extra] => auto_increment ) 
				$defval=isset($default[$ar['Field']])?$default[$ar['Field']]:'';
				$readonly_val=isset($readonly[ $ar['Field'] ])?'readonly':'';
				//echo '<h1>'.$readonly_val.'</h1>';
				if($ar['Field']=='id')
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo 'auto';
					echo '</div>';
				}
				elseif(substr($ar['Type'],-4)=='blob')
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo '<input type=file name=\''.$ar['Field'].'\' >';
					echo '</div>';
				}
				elseif(in_array($ar['Field'],array('recording_time','recorded_by')))
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';
						echo 'auto';
					echo '</div>';
				}
				else
				{
					echo '<div class="border">'.$ar['Field'].'</div>';
					echo '<div class="border">';				
						read_field($link,$tname,$ar['Field'],$defval,'no',$readonly_val);
					echo '</div>';
				}
				
			}

			echo '</div>';

			echo 
				'<div class="d-block" >
					<button type=submit class="btn btn-block btn-outline-success btn-sm m-0 p-0" name=action value=save_insert ><h5>Save</h5></button>
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					<input type=hidden name=tname value=\''.$tname.'\'>
				</div>';
				 
	echo'</form>';

}


function edit_old($link,$tname,$pk,$header='no')
{
	$sql='select * FROM `'.$tname.'` where id=\''.$pk.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result);
	
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
    echo '<div class="table-responsive">';
		echo '<table class="table table-striped table-sm table-bordered table-condensed">';
			if($header=='yes')
			{
				echo '<tr>';
				foreach($ar as $k=>$v)
				{
					echo '<td>'.$k.'</td>';
				}
				echo '</tr>';
			}
			
			echo '<tr>';
			foreach($ar as $k =>$v)
			{
				if($k=='id')
				{
					echo '<td>';
						ste_id_update_button($link,$tname,$v);
					echo '</td>';
				}
				elseif(substr(get_field_type($link,$tname,$k),-4)=='blob')
				{
					echo '<td>';
						echo '<input type=file name=\''.$k.'\' >';
					echo '</td>';
				}
				elseif(in_array($k,array('recording_time','recorded_by')))
				{
					echo '<td>'.$v.'</td>';
				}
				else
				{
					echo '<td>';		
						read_field($link,$tname,$k,$v);
					echo '</td>';
				}
			}
			echo '</tr>';
		echo '</table>';
	echo '</div>';
	echo'</form>';

}


function multiedit($link,$tname,$pk_ar,$header='no')
{
	$pk_csv='';
	foreach($pk_ar as $v)
	{
		$pk_csv=$pk_csv.'"'.$v.'"'.',';
	}
	$pk_csv=substr($pk_csv,0,-1);
	$sql='select * FROM `'.$tname.'` where id in ('.$pk_csv.')';
	//echo $sql;
	//return;
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	
	echo '<form method=post class="d-inline" enctype="multipart/form-data">';
	echo '<div class="table-responsive">';
	echo '<table class="table table-striped table-sm table-bordered table-condensed">';
	while($ar=get_single_row($result))
	{

				if($header=='yes')
				{
					echo '<tr>';
					foreach($ar as $k=>$v)
					{
						echo '<td>'.$k.'</td>';
					}
					echo '</tr>';
					$header='no';
				}
				
				echo '<tr>';
				foreach($ar as $k =>$v)
				{
					if($k=='id')
					{
						echo '<td>';
							echo '<input size=4 readonly type=text name=\'id_'.$v.'\' value=\''.$v.'\'>';
						echo '</td>';
					}
					elseif(substr(get_field_type($link,$tname,$k),-4)=='blob')
					{
						echo '<td>';
							echo '<input type=file name=\''.$k.'_'.$ar['id'].'\' >';
						echo '</td>';
					}
					elseif(in_array($k,array('recording_time','recorded_by')))
					{
						echo '<td>'.$v.'</td>';
					}
					else
					{
						echo '<td>';		
							read_field($link,$tname,$k.'_'.$ar['id'],$v);
						echo '</td>';
					}
				}
				echo '</tr>';

	}
	echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';

	echo '<tr><td><input type=submit name=action value=save></td></tr>';
	echo '</table>';
	echo '</div>';	
	echo'</form>';
}

function get_field_spec($link,$tname,$fname)
{
	$sql='select * from table_field_specification  where tname=\''.$tname.'\' and fname=\''.$fname.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	return $ar=get_single_row($result);	//return only first row, if mutiple, only forst one is returned
}

function read_field($link,$tname,$field,$value,$search='no',$readonly='')
{
	//echo '<h1>'.$readonly.'</h1>';

	$ftype=get_field_details($link,$tname,$field);
	$fspec=get_field_spec($link,$tname,$field);
	//print_r($fspec);
	if($fspec)
	{
		if($fspec['ftype']=='table')
		{
			if($readonly!='readonly')
			{
				mk_select_from_sql($link,'select distinct `'.$fspec['field'].'` from `'.$fspec['table'].'`',
				$fspec['field'],$fspec['fname'],$fspec['fname'],'',$value,$blank='yes');
			}
			else
			{
				echo '<input class="w-100" type=text  '.$readonly.' name=\''.$field.'\' value=\''.htmlentities($value,ENT_QUOTES).'\'>';
			}
		}
		else if($fspec['ftype']=='dtable')
		{
			//if($readonly!='readonly')
			//{
			$sql='select 
				distinct `'.$fspec['field'].'` , 
				concat_ws("|",'.$fspec['field_description'].') as description
			from `'.$fspec['table'].'`
			order by '.$fspec['field_description'];
			//echo $sql;
			mk_select_from_sql_with_description($link,$sql,
					$fspec['field'],$fspec['fname'],$fspec['fname'],'',$value,$blank='yes',$readonly);
			echo '<input placeholder="enter search string" type=text id=\'input_for_'.$fspec['fname'].'\' onchange="find_from_dd(this , \''.$fspec['fname'].'\');">';
		}
		elseif($fspec['ftype']=='date')
		{
			if($search=='yes')
			{
				echo '<input type=text '.$readonly.' name=\''.$field.'\' value=\''.$value.'\'>';
			}
			else
			{
				echo '<input type=date id=\''.$field.'\' name=\''.$field.'\' value=\''.$value.'\'>';
				$default=strftime("%Y-%m-%d");
				show_source_button($field,$default);
			}
		}
		elseif($fspec['ftype']=='time')
		{
			if($search=='yes')
			{
				echo '<input type=text  name=\''.$field.'\' value=\''.$value.'\'>';
			}
			else
			{
				echo '<input type=time id=\''.$field.'\'  '.$readonly.' name=\''.$field.'\' value=\''.$value.'\'>';
				$default=strftime("%H:%M");
				show_source_button($field,$default);
			}
		}				
		elseif($fspec['ftype']=='textarea')
		{
			echo '<pre><textarea class="w-100"  '.$readonly.' name=\''.$field.'\' >'.$value.'</textarea></pre>';
		}	
		else
		{
			echo 'not implemented';
		}
	}
	else
	{
		echo '<input class="w-100" type=text  '.$readonly.' name=\''.$field.'\' value=\''.htmlentities($value,ENT_QUOTES).'\'>';
	}
}

function update_one_field($link,$tname,$fname,$pk)
{
	if(strlen($_POST[$fname])==0)
	{
		$value=' NULL ';
	}
	else
	{
		$value=' \''.my_safe_string($link,$_POST[$fname]).'\' ';
	}
	//echo $fname.'<br>';
	update_one_field_with_value($link,$tname,$fname,$pk,$value);
}

function update_one_field_with_value($link,$tname,$fname,$pk,$value)
{
		$sql='update `'.$tname.'`
			set 
				`'.$fname.'` ='.$value.',
				recording_time=now(),
				recorded_by=\''.$_SESSION['login'].'\'
			where 
				id=\''.$pk.'\' ';
		//echo $sql;
	
	if(!$result=run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<p>Data not updated</p>';
	}
	else
	{
		if(rows_affected($link)==1)
		{
			//echo '<p>Saved</p>';				
		}
		else
		{
			//echo '<p>Result need no update</p>';
		}
	}
}

function update_one_field_blob($link,$tname,$fname,$name_fname,$pk)
{
	$data=file_to_str($link,$_FILES[$fname]);
	if(strlen($data)==0){return;}
	$sql='update `'.$tname.'`
		set 
			`'.$fname.'` =\''.$data.'\',
			recording_time=now(),
			recorded_by=\''.$_SESSION['login'].'\'			
		where 
			id=\''.$pk.'\' ';

	if(!$result=run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<p>Data not updated</p>';
	}
	else
	{
		if(rows_affected($link)==1)
		{
			//echo '<p>Saved</p>';
			update_one_field_with_value($link,$tname,$name_fname,$pk,'\''.$_FILES[$fname]['name'].'\'');				
		}
		else
		{
			//echo '<p>Result need no update</p>';
		}
	}
}

function update($link,$tname)
{
	foreach($_POST as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			//echo $k.'#<br>';
			update_one_field($link,$tname,$k,$_POST['id']);
		}
	}
	foreach($_FILES as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			update_one_field_blob($link,$tname,$k,$k.'_name',$_POST['id']);
		}
	}	
}



function update_from_array($link,$tname,$post)
{
	foreach($post as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			//echo $k.'#<br>';
			update_one_field($link,$tname,$k,$post['id']);
		}
	}
	foreach($_FILES as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			update_one_field_blob($link,$tname,$k,$k.'_name',$post['id']);
		}
	}	
}

function insert_from_array($link,$tname,$post)
{
	run_query($link,$GLOBALS['database'],'insert into `'.$tname.'` () values()');
	$id=last_autoincrement_insert($link);
	
	foreach($post as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			update_one_field($link,$tname,$k,$id);
		}
	}
	foreach($_FILES as $k=>$v)
	{
		if(!in_array($k,array('action','tname','session_name','id','recording_time','recorded_by')))
		{
			update_one_field_blob($link,$tname,$k,$k.'_name',$id);
		}
	}	
	return $id;
}


function insert_or_update_from_array($link,$tname,$post)
{
	if(insert_from_array($link,$tname,$post)==0)
	{
		update_from_array($link,$tname,$post);
	}
}


function list_available_tables($link)
{
	$sql_level='select distinct level from '.$GLOBALS['record_tables'].' order by level';
	$result_level=run_query($link,$GLOBALS['database'],$sql_level);
	echo '<h3>List of Available Tables</h3>';
	while($ar_level=get_single_row($result_level))
	{
		$sql='select * from '.$GLOBALS['record_tables'].' where level=\''.$ar_level['level'].'\'';
		$result=run_query($link,$GLOBALS['database'],$sql);

		while($ar=get_single_row($result))
		{
			//print_r($ar);
			show_manage_single_table_button($ar['table_name']);
		}
		echo '<hr>';
	}
}

function manage_stf($link,$post,$show_crud='yes')
{
	
	if(isset($post['tname']) && $show_crud=='yes')
	{
		echo '<div class="border border-dark m-2 p-2" >';
		echo '<h3>'.$post['tname'].': Choose any action below</h3>';
		$tname=$post['tname'];
		
		show_crud_button($tname,'add', 'Add Blank');
		show_crud_button($tname,'search');	//edit, remove inside it
		show_crud_button($tname,'list');	//edit, remove inside it
		echo '</div>';
	}

	//A done
	if($post['action']=='add')
	{
		echo '<h5>'.$post['action'].'</h5>';
		add($link,$post['tname']);
	}

	//B done
	elseif($post['action']=='edit')
	{
		echo '<h5>'.$post['action'].'</h5>';
		edit($link,$post['tname'],$post['id'],'yes');
		
		
	}

	//C done
	elseif($post['action']=='search')
	{
		echo '<h5>'.$post['action'].'</h5>';
		search($link,$post['tname']);
	}


	//1 no need, it is balnk insert -> view ->edit
	//elseif($post['action']=='insert')
	//{
	//	echo '<h5>'.$post['action'].'</h5>';
	//}

	//2
	if($post['action']=='update')
	{
		echo '<h5>'.$post['action'].'</h5>';
		update($link,$post['tname']);
		echo '<h5>updated at '.strftime("%Y-%m-%d %H:%M:%S").'</h5>';
		
		echo '<table class="table table-striped table-sm table-bordered">';
			view_row($link,$post['tname'],$post['id'],'yes');
		echo '</table>';
		
	}

	//3a done
	elseif($post['action']=='and_select')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname']);
	}
	//3b done
	elseif($post['action']=='or_select')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname'],$join='or');
	}
	//3c
	elseif($post['action']=='list')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname']);
	}

	//4 done
	elseif($post['action']=='delete')
	{
		echo '<h5>'.$post['action'].'</h5>';
		$sql='delete from `'.$tname.'` where id=\''.$post['id'].'\'';
		$result=run_query($link,$GLOBALS['database'],$sql);
		if($result)
		{
			echo '<h3>Deleted '.$tname.' id='.$post['id'].'</h3>';
		}
	}	

	
}


function manage_stf_with_direct_add($link,$post,$show_crud='yes')
{
	
	if(isset($post['tname']) && $show_crud=='yes')
	{
		echo '<div class="border border-dark m-2 p-2" >';
		echo '<h3>'.$post['tname'].': Choose any action below</h3>';
		$tname=$post['tname'];
		
		show_crud_button($tname,'add', 'Add Blank');
		show_crud_button($tname,'search');	//edit, remove inside it
		show_crud_button($tname,'list');	//edit, remove inside it
		echo '</div>';
	}

	//A done
	if($post['action']=='add')
	{
		echo '<h5>'.$post['action'].'</h5>';
		add_direct_with_default($link,$post['tname']);
	}

	elseif($post['action']=='save_insert')
	{
		$id=insert_from_array($link,$tname,$post);
		echo '<table class="table table-striped table-sm table-bordered">';
			view_row($link,$post['tname'],$id,'yes');
		echo '</table>';		
	}
	
	//B done
	elseif($post['action']=='edit')
	{
		echo '<h5>'.$post['action'].'</h5>';
		edit($link,$post['tname'],$post['id'],'yes');
	}

	//C done
	elseif($post['action']=='search')
	{
		echo '<h5>'.$post['action'].'</h5>';
		search($link,$post['tname']);
	}


	//1 no need, it is balnk insert -> view ->edit
	//elseif($post['action']=='insert')
	//{
	//	echo '<h5>'.$post['action'].'</h5>';
	//}

	//2
	if($post['action']=='update')
	{
		echo '<h5>'.$post['action'].'</h5>';
		update($link,$post['tname']);
		echo '<h5>updated at '.strftime("%Y-%m-%d %H:%M:%S").'</h5>';
		
		echo '<table class="table table-striped table-sm table-bordered">';
			view_row($link,$post['tname'],$post['id'],'yes');
		echo '</table>';
		
	}

	//3a done
	elseif($post['action']=='and_select')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname']);
	}
	//3b done
	elseif($post['action']=='or_select')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname'],$join='or');
	}
	//3c
	elseif($post['action']=='list')
	{
		echo '<h5>'.$post['action'].'</h5>';	
		select($link,$post['tname']);
	}

	//4 done
	elseif($post['action']=='delete')
	{
		echo '<h5>'.$post['action'].'</h5>';
		$sql='delete from `'.$tname.'` where id=\''.$post['id'].'\'';
		$result=run_query($link,$GLOBALS['database'],$sql);
		if($result)
		{
			echo '<h3>Deleted '.$tname.' id='.$post['id'].'</h3>';
		}
	}	

	
}


function mk_select_from_sql($link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no')
{
	//echo '<h1>'.$blank.'</h1>';
	$ar=mk_array_from_sql($link,$sql,$field_name);
	if($blank=='yes')
	{
		array_unshift($ar,"");
	}
	mk_select_from_array($select_name,$ar,$disabled,$default);
}
      
function mk_select_from_sql_with_description($link,$sql,$field_name,$select_name,$select_id,$disabled='',$default='',$blank='no',$readonly='')
{
	//echo '<h1>++'.$readonly.'</h1>';
	$ar=mk_array_from_sql_with_description($link,$sql,$field_name);
	if($blank=='yes')
	{
		array_unshift($ar,array("",""));
	}
	//print_r( $ar);
	mk_select_from_array_with_description($select_name,$ar,$disabled,$default,$readonly);
}

function mk_array_from_sql($link,$sql,$field_name)
{
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ret=array();
	while($ar=get_single_row($result))
	{
		$ret[]=$ar[$field_name];
	}
	return $ret;
}

function mk_array_from_sql_with_description($link,$sql,$field_name)
{
	$result=run_query($link,$GLOBALS['database'],$sql);
	$ret=array();
	while($ar=get_single_row($result))
	{
		$ret[]=array($ar[$field_name],$ar['description']);
	}
	return $ret;
}

function mk_select_from_array_with_description($name, $select_array,$disabled='',$default='',$readonly='')
{	
	//echo '<h1>--'.$readonly.'--</h1>';
	if($readonly=='readonly')
	{
		foreach($select_array as $key=>$value)
		{
			if($value[0]==$default)
			{
				echo '<input type=hidden '.$readonly.' name=\''.$name.'\' id=\''.$name.'\'  value=\''.$default.'\'>';
				echo $value[1].'('.$value[0].')';
			}
			else
			{

			}
		}
	

		return TRUE;
	}
	
	echo '<select  '.$disabled.'  id=\''.$name.'\'   name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
		//print_r($value);
		if($value[0]==$default)
		{
			echo '<option  selected value=\''.$value[0].'\' > '.$value[1].'('.$value[0].')'.' </option>';
		}
		else
		{
			echo '<option value=\''.$value[0].'\' > '.$value[1].'('.$value[0].')'.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function mk_select_from_array($name, $select_array,$disabled='',$default='')
{	
	echo '<select  '.$disabled.' name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
				//echo $default.'?'.$value;
		if($value==$default)
		{
			echo '<option  selected > '.$value.' </option>';
		}
		else
		{
			echo '<option > '.$value.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function file_to_str($link,$file)
{
	if($file['size']>0)
	{
		$fd=fopen($file['tmp_name'],'r');
		$size=$file['size'];
		$str=fread($fd,$size);
		return my_safe_string($link,$str);
	}
	else
	{
		return false;
	}
}


function show_source_button($link_element_id,$my_value)
{
	$element_id='source_for_'.$link_element_id;
	echo '<button onclick="sync_with_that(this,\''.$link_element_id.'\')"
				type=button
				class="btn btn-sm btn-outline-dark  no-gutters align-top"
				id=\''.$element_id.'\' 
				value=\''.$my_value.'\'>'.$my_value.'</button>';
}

function show_button_with_pk($tname,$type,$pk,$label='',$target='',$action='')
{
	if(strlen($label)==0){$label=$type;}
	echo '<div class="d-inline-block" ><form '.$action.' method=post '.$target.' class=print_hide>
	<button class="btn btn-outline-primary btn-sm" name=action value=\''.$type.'\' >'.$label.'('.$pk.')</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=tname value=\''.$tname.'\'>
	<input type=hidden name=id value=\''.$pk.'\'>
	</form></div>';
}
?>
