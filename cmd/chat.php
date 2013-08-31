<?php

function cmd_chat ($id, $args)
{
  global $players;
  
  if( isset($args) )
  {
    $msg = implode(" ", $args);
    
    say($players[$id].$msg);
  }
}

?>
