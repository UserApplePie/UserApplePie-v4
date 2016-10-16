<?php

  // View to display message sidebar

  use Libs\Language;

?>

<div class='col-lg-4 col-md-4'>
  <div class='panel panel-default'>
    <div class='panel-heading' style='font-weight: bold'>
      Private Messages
    </div>
    <ul class='list-group'>
      <li class='list-group-item'><span class='glyphicon glyphicon-inbox'></span> <a href='<?php echo DIR ?>MessagesInbox' rel='nofollow'>Inbox
        <?php
          // Check to see if there are any unread messages in inbox
          if($data['new_messages_inbox'] >= "1"){
            echo "<span class='badge'>${data['new_messages_inbox']} New</span>";
          }
        ?>
      </a></li>
      <li class='list-group-item'><span class='glyphicon glyphicon-road'></span> <a href='<?php echo DIR ?>MessagesOutbox' rel='nofollow'>Outbox</a></li>
      <li class='list-group-item'><span class='glyphicon glyphicon-pencil'></span> <a href='<?php echo DIR ?>NewMessage' rel='nofollow'>Create New Message</a></li>
    </ul>
  </div>
</div>
