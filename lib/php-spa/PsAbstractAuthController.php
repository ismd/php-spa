<?php
/**
 * Родительский класс для контроллеров, которым необходимо,
 *   чтобы пользователь был авторизован
 * @author ismd
 */

abstract class PsAbstractAuthController extends PsAbstractController {

    public function __construct($registry) {
        parent::__construct($registry);

        if (is_null($this->session->user)) {
            $this->redirect('/');
        }
    }
}
