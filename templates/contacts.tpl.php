

<span class="contact_name"><? echo $contact_array['name'] ?></span><br>
<?php if($contact_array['email']!==''){ ?><span class="contact_detail">Email:</span> <? echo $contact_array['email'] ?><br><?php } ?>
<?php if($contact_array['phone']!==''){ ?><span class="contact_detail">Phone:</span> <? echo $contact_array['phone'] ?><br><?php } ?>
<?php if($contact_array['mobile']!==''){ ?><span class="contact_detail">Mobile:</span> <? echo $contact_array['mobile'] ?><br><?php } ?>
<?php if($contact_array['fax']!==''){ ?><span class="contact_detail">Fax:</span> <? echo $contact_array['fax'] ?><br><?php } ?>

