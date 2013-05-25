<script>
var form_type = '<?php
if(isset($owner_array)){
    if($time_id !== 0){
        echo 't';
    } elseif($instance_id !== 0){
        echo 'i';
    } elseif($data_id !== 0){
        echo 'd';
    }
} else {
    echo 'o';
}
?>';

function nextStep(){
    $('next_step').value = 1;
}

function finished(){
    $('finished').value = 1;
}

<?php if(isset($owner_array)){ ?>
var owner_info = {owner_id: <?=$owner_array['owner_id']?>, contact_name: '<?=$owner_array['contact_name']?>', contact_email: '<?=$owner_array['email']?>', contact_phone: '<?=$owner_array['phone']?>', contact_mobile: '<?=$owner_array['mobile']?>', contact_fax: ''};

function fillFromOwner(){
    var contact_fields = ['contact_name','contact_email','contact_fax','contact_mobile','contact_phone'];
    contact_fields.each( function(inp){
		$(inp).value = owner_info[inp];
	});
}
<?php } ?>


function confirmDelete(type){
    var confirm_message = 'Are you sure you wish to remove this '+type+'? This action cannot be undone.';
    return confirm(confirm_message);
}

function validate(){
    var invalid_array = [];
    var message = '';
    var passwords_match = true;
    if(form_type == 'i'){
        var mandatory_fields = ['title','price','fax','mobile','phone'];
    } else if(form_type == 'd') {
        var mandatory_fields = ['title','price','fax','mobile','phone'];
    } else if(form_type == 't') {
        var mandatory_fields = ['title','price','fax','mobile','phone'];
    } else if(form_type == 'o') {
        var mandatory_fields = ['name','email','password'];
    }
    mandatory_fields.each( function(inp){
		if($(inp).value=='') invalid_array.push(inp);
	});
	if(form_type == 'o'){
		if($('password').value != $('conf_password').value){
			message += 'Your password does not match its confirm value.\n\n';
		}
	}
	if(invalid_array.length > 0){
		message += 'The following fields are mandatory: ';
		message += invalid_array.join(', ');
		message += '\n\n';
	}
	if(message.length > 0){
		alert(message);
		return false;
	}
}

</script>