<?php
/**
 * Родительский класс для контроллеров, которым необходимо,
 *   чтобы пользователь был авторизован
 *
 * @author ismd
 */

abstract class AbstractAuthController extends AbstractController {

    public function __construct($registry) {
        parent::__construct($registry);

        if (null == $this->session->user) {
            $this->redirect('/');
        }
    }
}
