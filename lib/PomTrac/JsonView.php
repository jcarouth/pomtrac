<?php
class PomTrac_JsonView extends Slim_View
{
    public function render($template)
    {
        //Hack: use the template name as the key we are looking for
        if (array_key_exists($template, $this->data)) {
            return json_encode($this->data[$template]);
        }

        //the flash messenger is unnecessary, remove it from the view data
        $userData = array_diff_key($this->data, array('flash' => null));
        return json_encode($userData);
    }
}
