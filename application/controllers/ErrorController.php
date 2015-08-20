<?php
/**
 * @author ismd
 */

class ErrorController extends PsController {

    public function indexAction() {
        http_response_code(404);
        $this->view->render('error');
    }
}
