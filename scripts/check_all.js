var checkflag="false";
var marked_row=new Array;
function check(a)
	{
	if(checkflag=="false")
		{
		for(i=0;
		i<a.length;
		i++)
			{
			a[i].checked=true
		}
		checkflag="true"
	}
	else
		{
		for(i=0;
		i<a.length;
		i++)
			{
			a[i].checked=false
		}
		checkflag="false"
	}
};