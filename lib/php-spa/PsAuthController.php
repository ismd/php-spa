<?php
/**
 * Родительский класс для контроллеров, которым необходимо,
 *   чтобы пользователь был авторизован
 * @author ismd
 */

class UnauthorizedSessionException extends Exception {
    protected $message = 'Unauthorized session';
}

abstract class PsAuthController extends PsController {

    public function __construct($registry) {
        parent::__construct($registry);

        if (is_null($this->getSession()->user)) {
            throw new UnauthorizedSessionException;
        }
    }
}
