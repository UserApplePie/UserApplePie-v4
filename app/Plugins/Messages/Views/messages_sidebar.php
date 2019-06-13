<?php
/**
* UserApplePie v4 Messages View Plugin Message Sidebar
*
* UserApplePie - Messages Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.3.0
*/

  // View to display message sidebar

  use Libs\Language;

?>


  <div class='card mb-3'>
    <div class='card-header h4'>
      Private Messages
    </div>
    <ul class='list-group list-group-flush'>
      <li class='list-group-item'><span class='fas fa-inbox'></span> <a href='<?php echo DIR ?>MessagesInbox' rel='nofollow'>Inbox
        <?php
          // Check to see if there are any unread messages in inbox
          if($data['new_messages_inbox'] >= "1"){
            echo " <span class='badge badge-primary'>${data['new_messages_inbox']} New</span>";
          }
        ?>
      </a></li>
      <li class='list-group-item'><span class='fas fa-road'></span> <a href='<?php echo DIR ?>MessagesOutbox' rel='nofollow'>Outbox</a></li>
      <li class='list-group-item'><span class='fas fa-pencil-alt'></span> <a href='<?php echo DIR ?>NewMessage' rel='nofollow'>Create New Message</a></li>
    </ul>
  </div>
