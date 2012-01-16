<?php
/**
 * Родительский класс для контроллеров, которым необходимо, чтобы пользователь был авторизован
 */

abstract class DefaultAuthController extends DefaultController {

    public function __construct($registry, $model=null) {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            die;
        }

        parent::__construct($registry, $model);
    }
}
?>
