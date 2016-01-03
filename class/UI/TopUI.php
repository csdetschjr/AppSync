<?php

namespace AppSync\UI;

/**
 * TopUI
 *
 * @author Chris Detsch
 */
class TopUI implements UI
{
    public function display(){
      $tpl = array();

      javascript('jquery');
      javascriptMod('appsync', 'organization');

      return \PHPWS_Template::process($tpl, 'appsync', 'top.tpl');
    }
}
