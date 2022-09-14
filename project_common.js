function sync_with_that(me,that_element_id)
{
	//alert(me.getAttribute('data-type'));
	target=document.getElementById(that_element_id);
	target.value=me.value
	var event = new Event('change');
	target.dispatchEvent(event);
}

function  find_from_dd(me,idd)
{
		var option;
		target=document.getElementById(idd);
		//alert(me.value);
		var selectLength = document.getElementById(idd).length;
		for(i=0; i<selectLength;i++)
		{
				if (target[i].text.toLowerCase().search(me.value.toLowerCase())!=-1) 
				{
						//alert(target[i].text);
						//target.selectedIndex=i;
						//return;
						option = document.createElement("option");
						option.text = target[i].text
						option.value = target[i].value
						target.prepend(option); 
						i++;
				}
				else
				{

				}
		}
		target.selectedIndex=0;
		//alert("No record found having >>>>"+me.value+"<<<<");
}
	
