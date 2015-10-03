<?php
namespace Users\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    /**         
     * Shortcut for Controller::set('title_for_layout', ...)
     *      
     * @param string $titleForLayout The title to use on layout's title tag
     * @return void
     */     
    public function title($titleForLayout)
    {   
        $this->set('title_for_layout', $titleForLayout);
    }   
    
    /**
     * Shortcut for Controller::set('description_for_layout', ...)
     *
     * @param string $descriptionForLayout The description to use as
     *  meta-description on layout's head tag
     * @return void
     */
    public function description($descriptionForLayout)
    {
        $this->set('description_for_layout', $descriptionForLayout);
    }
}
