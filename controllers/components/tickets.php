<?php

class TicketsComponent 
{ 
    // Create a new ticket by providing the data to be stored in the ticket. 
    function set($info = null) 
    { 
        $this->garbage(); 
        if ($info) 
        { 
            $ticketObj = new Ticket(); 
            $data['Ticket']['hash'] = md5(time()); 
            $data['Ticket']['data'] = $info; 

            if ($ticketObj->save($data)) 
            { 
                return $data['Ticket']['hash']; 
            } 
        } 
        return false; 
    } 
     
    // Return the value stored or false if the ticket can not be found. 
    function get($ticket = null) 
    { 
        $this->garbage(); 
        if ($ticket) 
        { 
            $ticketObj = new Ticket(); 
            $data = $ticketObj->findByHash($ticket); 
            if (is_array($data) && is_array($data['Ticket'])) 
            { 
                // optionally auto-delete the ticket -> this->del($ticket); 
                return $data['Ticket']['data']; 
            } 
        } 
        return false; 
    } 

    // Delete a used ticket 
    function del($ticket = null) 
    { 
        $this->garbage(); 
        if ($ticket) 
        { 
            $ticketObj = new Ticket(); 
            $data = $ticketObj->findByHash($ticket); 
            if ( is_array($data) && is_array($data['Ticket']) ) 
            { 
                return $data = $ticketObj->delete($data['Ticket']['id']); 
            } 
        } 
        return false; 
    } 

    // Remove old tickets 
    function garbage() 
    {         
        $deadline = date('Y-m-d H:i:s', time() - (24 * 60 * 60 * 5)); // keep tickets for 5 days. 
		App::import('model','Core.Ticket');
        $ticketObj = new Ticket(); 
        $data = $ticketObj->query('DELETE from tickets WHERE created < \''.$deadline.'\''); 
    } 
} 

?>