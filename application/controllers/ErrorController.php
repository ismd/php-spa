<?php
/**
 * @author ismd
 */

class ErrorController extends PsController {

    public function index() {
        $this->view->render('error');
    }
}
