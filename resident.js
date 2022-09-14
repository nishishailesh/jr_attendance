function mark_unsaved(element_id)
{
	target=document.getElementById(element_id);
	target.classList.remove("btn-success");
	target.classList.add("btn-warning");
}
function getDaysInMonth(month,year) 
{
  // Here January is 1 based
  //Day 0 is the last day in the previous month
 return new Date(year, month, 0).getDate();

// Here January is 0 based
// return new Date(year, month+1, 0).getDate();

};

//console.log(getDaysInMonth(1, 2012));
