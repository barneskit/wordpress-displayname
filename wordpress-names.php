<?php

class qa_html_theme_layer extends qa_html_theme_base
{

  function post_meta_who($post, $class) 
  {
    require_once QA_INCLUDE_DIR.'qa-app-users.php'; 
    if (isset($post['who'])) {
      $this->output('<SPAN CLASS="'.$class.'-who">');

      if (strlen(@$post['who']['prefix']))
	$this->output('<SPAN CLASS="'.$class.'-who-pad">'.$post['who']['prefix'].'</SPAN>');

      if (isset($post['who']['data']))
	  if (isset($post['raw']['ouserid']))
	  {
	      $user_info = get_userdata($post['raw']['ouserid']);
	  }
          else
          {
              $user_info = get_userdata($post['raw']['userid']);
	  }
      $display_name = $user_info->display_name;
      $user_login = $user_info->user_login;
      $this->output('<SPAN CLASS="'.$class.'-who-data"><a href=/user/'.$user_login.'>'.$display_name.'</a></SPAN>');

      if (isset($post['who']['title']))
	$this->output('<SPAN CLASS="'.$class.'-who-title">'.$post['who']['title'].'</SPAN>');

      // You can also use $post['level'] to get the author's privilege level (as a string)

      if (isset($post['who']['points'])) {
	$post['who']['points']['prefix']='('.$post['who']['points']['prefix'];
	$post['who']['points']['suffix'].=')';
	$this->output_split($post['who']['points'], $class.'-who-points');
      }

      if (strlen(@$post['who']['suffix']))
	$this->output('<SPAN CLASS="'.$class.'-who-pad">'.$post['who']['suffix'].'</SPAN>');

      $this->output('</SPAN>');
    }
  }

  function post_meta($post, $class, $prefix=null, $separator='<BR/>')
  {
    $this->output('<SPAN CLASS="'.$class.'-meta">');
    
    if (isset($prefix))
      $this->output($prefix);
    
    $order=explode('^', @$post['meta_order']);
    
    foreach ($order as $element)
      switch ($element) {
      case 'what':
	$this->post_meta_what($post, $class);
	break;
	
      case 'when':
	$this->post_meta_when($post, $class);
	break;
	
      case 'where':
	$this->post_meta_where($post, $class);
	break;
	
      case 'who':
	$this->post_meta_who($post, $class);
	break;
      }
    
    $this->post_meta_flags($post, $class);
    
    if (!empty($post['when_2'])) {
      $this->output($separator);
      
      foreach ($order as $element)
	switch ($element) {
	case 'when':
	  $this->output_split($post['when_2'], $class.'-when');
	  break;
	  
	case 'who':
	         
	  $str= $post['who_2']['data'];
	  $DOM = new DOMDocument;
	  $DOM->loadHTML($str);
	  $items = $DOM->getElementsByTagName('a');
	  for ($i = 0; $i < $items->length; $i++)
	    $name =  $items->item($i)->nodeValue;
	  $details = get_user_by('login',  $name);
	  $post['who_2']['data'] = '<a href="./user/'.$name.'" class="qa-user-link">'.$details->display_name.'</a>';
	  $this->output_split($post['who_2'], $class.'-who');
	  break;
	}
    }

    $this->output($separator);
    $this->output('</SPAN>');

  }

}

?>