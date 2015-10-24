<?php

/**
 * MapController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class MapController extends Controller
{

    /**
     * Map表示
     *
     * @return string
     */
    public function indexAction()
    {
        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' ----');
        
        return $this->render(array(), null, null);
    }
    
}
