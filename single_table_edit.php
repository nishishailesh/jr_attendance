<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'single_table_edit_common.php';
	////////User code below/////////////////////
echo '		  <link rel="stylesheet" href="project_common.css">
		  <script src="project_common.js"></script>	
		  <script src="resident.js"></script>';	
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
$user=get_user_info($link,$_SESSION['login']);
//$auth=explode(',',$user['authorization']);
//print_r($user);

echo '<h3 class="text-success">Junior Resident attendance</h3>';
echo '<div class="d-inline-block bg-light p-3 border" >
		<form method=post class=print_hide>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
			echo 'Enter Month and Year for which attendance is to be submitted<br>';

			echo 'Month:<input  type=number size=4 name=month>';
			echo 'Year:<input  type=number size=8 name=year>';
			echo '<button class="btn btn-outline-primary btn-sm" name=action value=add_resident_attendence>Mark Attandance</button>

		</form>
	</div>';
echo '<div>';	//div after main menu


if($_POST['action']=='final_submit')
{
		$sql='update attendance set
						final_submit=1,
						recorded_by=\''.$_SESSION['login'].'\',
						recording_time=now()
					where
						resident_id=\''.$_POST['resident_id'].'\' and
						month=\''.text_to_int($_POST['month']).'\' and 
						year=\''.text_to_int($_POST['year']).'\'
						';
		//echo $sql;
		$result=run_query($link,$GLOBALS['database'],$sql);
}


if($_POST['action']=='save')
{
	$sql='insert into attendance (resident_id,month,year,present_days,
						leave_without_pay,sick_leave,casual_leave,weekly_off,other_paid_leave,remark,
						recorded_by,recording_time)
						
						values
						(
						\''.$_POST['resident_id'].'\',
						\''.text_to_int($_POST['month']).'\',
						\''.text_to_int($_POST['year']).'\',
						\''.text_to_int($_POST['present_days']).'\',
						\''.text_to_int($_POST['leave_without_pay']).'\',
						\''.text_to_int($_POST['sick_leave']).'\',
						\''.text_to_int($_POST['casual_leave']).'\',
						\''.text_to_int($_POST['weekly_off']).'\',
						\''.text_to_int($_POST['other_paid_leave']).'\',
						\''.$_POST['remark'].'\',
						\''.$_SESSION['login'].'\',
						now())';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	if($result==False)
	{
		//echo 'record exist';
		$sql='update attendance set
						present_days=\''.text_to_int($_POST['present_days']).'\',
						leave_without_pay=\''.text_to_int($_POST['leave_without_pay']).'\',
						sick_leave=\''.text_to_int($_POST['sick_leave']).'\',
						casual_leave=\''.text_to_int($_POST['casual_leave']).'\',
						weekly_off=\''.text_to_int($_POST['weekly_off']).'\',
						other_paid_leave=\''.text_to_int($_POST['other_paid_leave']).'\',
						remark=\''.$_POST['remark'].'\',
						recorded_by=\''.$_SESSION['login'].'\',
						recording_time=now()
					where
						resident_id=\''.$_POST['resident_id'].'\' and
						month=\''.text_to_int($_POST['month']).'\' and 
						year=\''.text_to_int($_POST['year']).'\'
						';
		//echo $sql;
		$result=run_query($link,$GLOBALS['database'],$sql);		
	}
}


$sql='
		select
			id,
			name ,
			department,
			aadhar,
			current_date() as today,
			date_of_joining,
			date_add(date_add(date_of_joining,INTERVAL 3 YEAR), Interval -1 day) as last_day,
			datediff(date_add(date_add(date_of_joining,INTERVAL 3 YEAR), Interval -1 day),current_date() ) as remaining_days

		from 
			resident 
		where 
			department=\''.$user['department'].'\'
			and
			datediff(date_add(date_add(date_of_joining,INTERVAL 3 YEAR), Interval -1 day),current_date() ) >-65
		order by 
			remaining_days desc';
			
$result=run_query($link,$GLOBALS['database'],$sql);



if(isset($_POST['month']))
{
	if(
			$_POST['month']>=1 && $_POST['month']<=12 &&
			$_POST['year']>=strftime("%Y")-1 && $_POST['year']<=strftime("%Y")
	)
	{
		$result=run_query($link,$GLOBALS['database'],$sql);
		take_resident_attendance($link,$result,isset($_POST['resident_id'])?$_POST['resident_id']:'',$_POST['month'],$_POST['year']);
		
		echo '<form method=post action=print.php>
				<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
				<input type=hidden name=month value=\''.$_POST['month'].'\'>
				<input type=hidden name=year value=\''.$_POST['year'].'\'>
				<button class="btn btn-outline-primary btn-sm" name=action value=print target=_blank>Print</button>
		</form>';
		print_attendence($link,$user['department'],$_POST['month'],$_POST['year']);
	}
	else 
	{
		echo  '<h3 class=text-danger>month  and year inappropriate</h3>';
	}


}


//////////////user code ends////////////////
echo '</div>';	//for div after main menu

tail();

//echo '<pre>';print_r($_POST);print_r($_FILES);echo '</pre>';

//////////////Functions///////////////////////

//list_available_tables($link);

//manage_stf_with_direct_add($link,$_POST);

function print_attendence($link,$dept,$month,$year)
{
	$field_list='resident.id,aadhar,name,department,date_of_joining,month,year,present_days,leave_without_pay,sick_leave,casual_leave,weekly_off,other_paid_leave,remark';
	$sql='select '.$field_list.' from  resident , attendance
			where 
				resident.department=\''.$dept.'\'
				and
				resident.id=attendance.resident_id
				and
				attendance.year=\''.$year.'\'
				and
				attendance.month=\''.$month.'\'
				and
				final_submit=1
				';
		echo $sql;
		echo '<div id=print_area>';
		view_sql_result_as_table($link,$sql,$show_hide='yes');
		echo '</div>';
}



function text_to_int($str)
{
	return (int)($str);
}

function take_resident_attendance($link,$result,$resident_id,$month,$year)
{

	echo '<div>';	
	
       echo '<table border=1 class="table-striped table-hover">';

                 echo '<tr bgcolor=lightgreen>';
				 echo '<td colspan=10 class=text-danger >Edit -> Save -> Final Submit -> Print</td>
					<td colspan=6  class=text-danger >Keep attendance empty where not relevent</td>
					<td colspan=3  class=text-danger >No change after submit</td>';
                 echo '</tr>';
                 echo '<tr>
                 <td>Resident Id</td>
                 <td>Name</td>
                 <td>Dept</td>
                 <td>AADHAR</td>
                 <td>Today</td>
                 <td>Date_of_Joining</td>
                 <td>Last_day</td>
                 <td>Days remaining<br>As on today</td>
                 <td>Month</td>
                 <td>Year</td>
                 <td>Present</td>
                 <td>LWP</td>
                 <td>SL</td>
                 <td>CL</td>
                 <td>Weekly Off</td>
                 <td>Other paid leave</td>
                 <td>Remark</td>
                 <td>Action</td>
                 <td>Action</td>
                 </tr>';
				 					
			$first_data='yes';

			while($array=get_single_row($result))
			{
				//print_r($array);

				$edit_sql='select * from attendance where resident_id=\''.$array['id'].'\' and month=\''.$month.'\' and year=\''.$year.'\'';
				//echo $edit_sql;
				$edit_result=run_query($link,$GLOBALS['database'],$edit_sql);
				$edit_ar=get_single_row($edit_result);
				$fs=isset($edit_ar['final_submit'])?$edit_ar['final_submit']:0;
				//print_r($edit_ar);
				if($resident_id==$array['id']  && $_POST['action']!='save' && $_POST['action']!='final_submit'){$disabled='';}else{$disabled='disabled';}
				if($fs==1){$disabled='disabled';}
                echo '<tr>';
                
                echo '<form method=post>';
					echo '<input  type=hidden name=resident_id value=\''.$array['id'].'\'>';
					foreach($array as $k=>$v)
					{
						echo '<td>'.$v.'</td>';
					}    

						echo '<td><input  readonly type=number size=4 value=\''.$month.'\'  name=month></td>';
						echo '<td><input  readonly  type=number size=8    value=\''.$year.'\'  name=year></td>';
						echo '<td><input  id=pd_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['present_days']:'').'\'  name=present_days></td>';
						echo '<td><input  id=lwp_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['leave_without_pay']:'').'\'  name=leave_without_pay></td>';
						echo '<td><input  id=sl_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['sick_leave']:'').'\'  name=sick_leave></td>';
						echo '<td><input  id=cl_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['casual_leave']:'').'\'  name=casual_leave></td>';
						echo '<td><input  id=wo_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['weekly_off']:'').'\'  name=weekly_off></td>';
						echo '<td><input  id=opl_'.$array['id'].' '.$disabled.' type=number size=4  value=\''.(isset($edit_ar['month'])?$edit_ar['other_paid_leave']:'').'\'  name=other_paid_leave></td>';
						echo '<td><input  '.$disabled.' type=text size=10  value=\''.(isset($edit_ar['month'])?$edit_ar['remark']:'').'\'  name=remark></td>';

					
					if($fs!=1)
					{
						if($array['id']==$resident_id && $_POST['action']!='save' && $_POST['action']!='final_submit')
						{
							echo '<td><button  class="btn btn-danger  btn-sm"  onclick="return validate_save(\''.$array['id'].'\',\''.$year.'\',\''.$month.'\')" type=submit name=action value=save>Save</button></td>';
						}
						else
						{					
							echo '<td><button  class="btn btn-success  btn-sm"  type=submit name=action value=edit>Edit</button></td>';
							echo '<td><button  class="btn btn-success  btn-sm"  type=submit name=action value=final_submit>Final Submit</button></td>';
						}
						
						
					}
					else
					{
						echo '<td>submitted</td>';
						echo '<td></td>';
					}
					echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
				 echo '</form>';
                echo '</tr>';
        }
        echo '</table>';
        
		
	echo '</div>';	
	
}

?>

<script>
function validate_save(resident_id,year,month)
{
	pd=document.getElementById('pd_'+resident_id);
	lwp=document.getElementById('lwp_'+resident_id);
	sl=document.getElementById('sl_'+resident_id);
	cl=document.getElementById('cl_'+resident_id);
	wo=document.getElementById('wo_'+resident_id);
	opl=document.getElementById('opl_'+resident_id);
	entered_total_days=Number(pd.value)+Number(lwp.value)+Number(sl.value)+Number(cl.value)+Number(wo.value)+Number(opl.value);
	expected_total_days=new Date(2022, 2 , 0).getDate();	//2 is march, 0 means day before 1st day, that is february month, its days are 28
	expected_total_days=new Date(year, month , 0).getDate();	//2 is march, 0 means day before 1st day, that is february month, its days are 28
	
	//alert(pd.value+'+'+lwp.value+'+'+sl.value+'+'+cl.value+'+'+wo.value+'+'+opl.value+'='+(Number(pd.value)+Number(lwp.value)+Number(sl.value)+Number(cl.value)+Number(wo.value)+Number(opl.value)));
	x=pd.value+'+'+lwp.value+'+'+sl.value+'+'+cl.value+'+'+wo.value+'+'+opl.value+'='+(Number(pd.value)+Number(lwp.value)+Number(sl.value)+Number(cl.value)+Number(wo.value)+Number(opl.value));
	y='expected='+expected_total_days+ '=====>entered='+entered_total_days;
	if(expected_total_days==entered_total_days || entered_total_days==0)
	{
		alert(x+'\n'+y+'\n'+"saving data...");
		return true;
	}
	else
	{
		alert(x+'\n'+y+"\nentered days are not equal to expected days.\ncan not save data...");
		return false;
	}
	
}
</script>
