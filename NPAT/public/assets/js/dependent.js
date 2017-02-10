
/*
*
* Get an ajax call to display metrics categories based on project and resource
*/
$('#projectName').on('change',function(e){
    $("#resourceName").select2({
        placeholder: "Select People"
    }).empty().trigger("change");
  var project_id = $(this).val();
  getAjax({'project_id' : project_id}, '/ajax-resourcecategory/'+""+project_id , function(data){
    console.log(data);
    $('#resourceName').empty();
     $('#resourceName').append('<option value="">Select People</option>');    
    $.each(data,function(feedback_form,resourcecatobj){
      $('#resourceName').append('<option value="'+resourcecatobj.id+'">'+resourcecatobj.name+'</option>');
    });
  });
});


/*
*
* return an ajax call with data 
*/
function getAjax(data, url, callback)
{
	$.ajax({
		url: url,
		data: data,
	}).done(function(data) {
		callback(data);
	})
}
