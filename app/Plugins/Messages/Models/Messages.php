<?php

// Message Model Class Database Goods

namespace App\Plugins\Messages\Models;

use App\System\Models;

class Messages extends Models {

  // Gets all inbox messages for current user from database
  public function getInbox($where_id, $limit){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
						m.id,
            m.to_userID,
            m.from_userID,
            m.subject,
            m.content,
            m.date_read,
            m.date_sent,
            m.to_delete,
            mf.userID,
            mf.username
					FROM
						".PREFIX."messages m
          LEFT JOIN
            ".PREFIX."users mf
            ON m.from_userID = mf.userID
					WHERE
						m.to_userID = :userID
          AND
            m.to_delete = :to_delete
          ORDER BY
            m.date_sent DESC
          $limit
					",
				array(':userID' => $where_id, ':to_delete' => 'false'));
			return $data;
		}else{
			return false;
		}
  }

  // Gets all outbox messages for current user from database
  public function getOutbox($where_id, $limit){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
						m.id,
            m.to_userID,
            m.from_userID,
            m.subject,
            m.content,
            m.date_read,
            m.date_sent,
            m.from_delete,
            mt.userID,
            mt.username
					FROM
						".PREFIX."messages m
          LEFT JOIN
            ".PREFIX."users mt
            ON m.to_userID = mt.userID
					WHERE
						m.from_userID = :userID
          AND
            m.from_delete = :from_delete
          ORDER BY
            m.date_sent DESC
          $limit
					",
				array(':userID' => $where_id, ':from_delete' => 'false'));
			return $data;
		}else{
			return false;
		}
  }

  // Gets requested message for current user from database
  // TODO :: Add check to make sure user is owner of requested message
  public function getMessage($where_id, $u_id){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
						m.id,
            m.to_userID,
            m.from_userID,
            m.subject,
            m.content,
            m.date_read,
            m.date_sent,
            mf.userID,
            mf.username
					FROM
						".PREFIX."messages m
          LEFT JOIN
            ".PREFIX."users mf
            ON m.from_userID = mf.userID
					WHERE
						m.id = :messageID
          LIMIT 1
					",
				array(':messageID' => $where_id));
        // Mark message as read if not already
        $data_check = $this->db->select("
            SELECT
              *
            FROM
              ".PREFIX."messages
            WHERE
              id = :id
            AND
              to_userID = :to_userID
            AND
              date_read IS NULL
            ",
          array(':id' => $where_id, ':to_userID' => $u_id));
          $count = count($data_check);
          if($count > 0){
            $data_dr = array('date_read' => date('Y-m-d G:i:s'));
      			$where = array('id' => $where_id);
      			$this->db->update(PREFIX."messages",$data_dr,$where);
          }
			return $data;
		}else{
			return false;
		}

  }

  // Gets the UserID of a given username
  public function getUserIDFromUsername($where_username){
    $data = $this->db->select("SELECT userID FROM ".PREFIX."users WHERE username = :username",
			array(':username' => $where_username));
		return $data[0]->userID;
  }

  // Puts new/reply message data into database
  public function sendmessage($to_userID, $from_userID, $subject, $content){
    // Format the Content for database
		$content = nl2br($content);
		// Update messages table
		$query = $this->db->insert(PREFIX.'messages', array('to_userID' => $to_userID, 'from_userID' => $from_userID, 'subject' => $subject, 'content' => $content));
		$count = count($query);
		// Check to make sure something was updated
		if($count > 0){
      // Message was updated in database, now we send the to user an email notification.
      // Get to user's email from id
      $data = $this->db->select("SELECT email, username, privacy_pm FROM ".PREFIX."users WHERE userID = :userID",
  			array(':userID' => $to_userID));
  		$email = $data[0]->email;
      $username = $data[0]->username;
      $privacy_pm = $data[0]->privacy_pm;
      // Get from user's data
      $data2 = $this->db->select("SELECT username FROM ".PREFIX."users WHERE userID = :userID",
        array(':userID' => $from_userID));
      $from_username = $data2[0]->username;
      // Check to see if user has privacy pm enabled
      if($privacy_pm == "true"){
        //EMAIL MESSAGE USING PHPMAILER
        $mail = new \Libs\PhpMailer\Mail();
        $mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
        $mail->addAddress($email);
        $mail_subject = " " . SITETITLE . " - New Private Message";
        $mail->subject($mail_subject);
        $body = "Hello {$username}<br/><br/>";
        $body .= "{$from_username} sent you a new Private Message on " . SITETITLE . "<hr/>";
        $body .= "<b>:Subject:</b><Br/> {$subject}<hr/> <b>Content:</b><br/> {$content}<hr/>";
        $body .= "<b><a href=\"" . SITEURL . "\">Go to " . SITETITLE . "</a></b>";
        $mail->body($body);
        $mail->send();
      }
			return true;
		}else{
			return false;
		}
  }

  // Deletes selected messages from inbox where userID is owner
  public function deleteMessageInbox($u_id, $msg_id){
    // Check to make sure message is not already marked as read
    $query = $this->db->select('SELECT * FROM '.PREFIX.'messages WHERE id = :msg_id AND to_userID = :u_id',
        array(':msg_id' => $msg_id, ':u_id' => $u_id));
    $count = count($query);
    if($count == "1")
    {
      $data_del = $this->db->update(PREFIX.'messages', array('to_delete' => 'true'), array('id' => $msg_id));
      $count_del = count($data_del);
      if($count_del > 0){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  // Mark selected messages as read in messages database where userID is owner
  public function markReadMessageInbox($u_id, $msg_id){
    // Check to make sure message is not already marked as read
    $query = $this->db->select('SELECT * FROM '.PREFIX.'messages WHERE id = :msg_id AND to_userID = :u_id AND date_read IS NULL',
        array(':msg_id' => $msg_id, ':u_id' => $u_id));
    $count = count($query);
    if($count == "1")
    {
      $data_del = $this->db->update(PREFIX.'messages', array('date_read' => date('Y-m-d G:i:s')), array('id' => $msg_id));
      $count_del = count($data_del);
      if($count_del > 0){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  // Deletes selected messages from outbox where userID is owner
  public function deleteMessageOutbox($u_id, $msg_id){
    // Make sure message is related to user and exists
    $query = $this->db->select('SELECT * FROM '.PREFIX.'messages WHERE id = :msg_id AND from_userID = :u_id',
        array(':msg_id' => $msg_id, ':u_id' => $u_id));
    $count = count($query);
    if($count == "1")
    {
      $data_del = $this->db->update(PREFIX.'messages', array('from_delete' => 'true'), array('id' => $msg_id));
      $count_del = count($data_del);
      if($count_del > 0){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

  // Gets total number of unread messages from database for selected user
  public function getUnreadMessages($where_id){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
            *
					FROM
						".PREFIX."messages
					WHERE
						to_userID = :userID
          AND
            date_read IS NULL
          AND
            to_delete = :to_delete
					",
				array(':userID' => $where_id, ':to_delete' => 'false'));
        $count = count($data);
        if($count > 0){
          return $count;
        }else{
          $count = "0";
          return $count;
        }
			return $count;
		}else{
      $count = "0";
      return $count;
    }
  }

  // Gets total number of messages from database for selected user's inbox
  public function getTotalMessages($where_id){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
            *
					FROM
						".PREFIX."messages
					WHERE
						to_userID = :userID
          AND
            to_delete = :to_delete
					",
				array(':userID' => $where_id, ':to_delete' => 'false'));
        $count = count($data);
        if($count > 0){
          return $count;
        }else{
          $count = "0";
          return $count;
        }
			return $count;
		}else{
      $count = "0";
      return $count;
    }
  }

  // Gets total number of messages from database for selected user's outbox
  public function getTotalMessagesOutbox($where_id){
    if(ctype_digit($where_id)){
			$data = $this->db->select("
					SELECT
            *
					FROM
						".PREFIX."messages
					WHERE
						from_userID = :userID
          AND
            from_delete = :from_delete
					",
				array(':userID' => $where_id, ':from_delete' => 'false'));
        $count = count($data);
        if($count > 0){
          return $count;
        }else{
          $count = "0";
          return $count;
        }
			return $count;
		}else{
      $count = "0";
      return $count;
    }
  }

  // Check to see if requested message exist and is related to user
  public function checkMessagePerm($u_id, $m_id){
    $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."messages
        WHERE
          id = :m_id
        AND
          (to_userID = :userID OR from_userID = :userID)
        ",
      array(':userID' => $u_id, ':m_id' => $m_id));
      $count = count($data);
      if($count > 0){
        return true;
      }else{
        return false;
      }
  }

  // Hidden Auto Check to make sure that messages that are marked
  // for delete by both TO and FROM users are removed from database
  public function cleanUpMessages(){
    $this->db->delete_open(PREFIX."messages WHERE to_delete = 'true' AND from_delete = 'true'");
  }

  // Get percentage of two numbers
  public function getPercentage($num_amount, $num_total) {
    if($num_amount == '0'){
      $count = '0';
    }else{
      $count = $num_amount / $num_total;
      $count = $count * 100;
      $count = number_format($count, 0);
    }
    return $count;
  }

  // Check to see if user's outbox is full
  public function checkMessageQuota($u_id){
    $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."messages
        WHERE
          from_userID = :userID
        AND
          from_delete = :from_delete
        ",
      array(':userID' => $u_id, ':from_delete' => 'false'));
      $count = count($data);
      if($count >= MESSAGE_QUOTA_LIMIT){
        return true;
      }else{
        return false;
      }
  }

  // Check to see to user's inbox is full or not
  public function checkMessageQuotaToUser($to_userID){
    $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."messages
        WHERE
          to_userID = :userID
        AND
          to_delete = :to_delete
        ",
      array(':userID' => $to_userID, ':to_delete' => 'false'));
      $count = count($data);
      if($count >= MESSAGE_QUOTA_LIMIT){
        return false;
      }else{
        return true;
      }
  }

}
