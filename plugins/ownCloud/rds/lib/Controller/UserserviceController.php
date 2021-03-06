<?php

namespace OCA\RDS\Controller;

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use \OCA\RDS\Service\UserserviceportService;
use OCP\AppFramework\Http\RedirectResponse;

class UserserviceController extends Controller {
    private $userId;
    private $service;

    use Errors;

    public function __construct( $AppName, IRequest $request, UserserviceportService $service, $userId) {
        parent::__construct( $AppName, $request );
        $this->userId = $userId;
        $this->service = $service;
    }

    /**
    * Returns a list with all services from RDS for user.
    *
    * @return array a list with object with key 'jwt', see $this->show()
    *
    * @NoAdminRequired
    * @NoCSRFRequired
    */

    public function index() {
        return $this->handleNotFound(function () {
            return $this->service->findAll($this->userId);
        });
    }

    /**
    * Returns a single service from user in RDS.
    *
    * @param string $id
    * @return object an object with jwt encoded object with keys
    * 'id', 'authorize_url', 'date'
    *
    * @NoAdminRequired
    * @NoCSRFRequired
    */

    public function show( $id ) {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->find($id, $this->userId);
        });
    }

    /**
    * Removes a single service from the user in RDS.
    *
    * @param string $id
    * @return bool returns true for success, else false
    *
    * @NoAdminRequired
    */

    public function destroy( $id ) {
        return $this->handleNotFound(function () use ($id) {
             return $this->service->delete( $id, $this->userId );
        });
    }

    /**
    * Register a new service for the user in RDS.
    *
    * @param string $id
    * @return bool returns true for success, else false
    *
    * @NoAdminRequired
    * @NoCSRFRequired
    */

    public function register( $code, $state ) {
        $service = null;
        return $this->handleNotFound(function () use ($code, $state) {
            $result = $this->service->register( "Owncloud", $code, $state, $this->userId, $this->service );
            $params = [];
             if($result ){
                return new TemplateResponse( 'rds', "code.done", $params );
             }
             return new TemplateResponse( 'rds', "code.failure", $params );
        });
    }
}
