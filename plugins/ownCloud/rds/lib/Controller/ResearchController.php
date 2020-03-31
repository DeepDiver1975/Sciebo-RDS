<?php

namespace OCA\RDS\Controller;

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCA\RDS\Service\ResearchService;


class ResearchController extends Controller
{
    private $userId;
    private $service;
    
    public function __construct($AppName, IRequest $request, ResearchService $service, $userId)
    {
        parent::__construct($AppName, $request);
        $this->userId = $userId;
        $this->service = $service;
    }

    /**
     * Returns all research in rds for userId
     *
     * @param integer $id
     * @return string returns json
     * 
     * @NoAdminRequired
     */
    public function index() {
        return new JSONResponse($this->service->findAll($this->userId));
    }

    /**
     * Returns all Research informations in rds for given id
     *
     * @param integer $id
     * @return string returns json
     *
     * @NoCSRFRequired
     * @NoAdminRequired
     */
    public function show($id) {
        return $this->handleNotFound(function () use ($id) {
            return new JSONResponse($this->service->find($id, $this->userId));
        });
    }

    /**
     * Create a new Research.
     *
     * @return string returns the Research object as json
     * 
     * @NoAdminRequired
     */
    public function create() {
        return new JSONResponse($this->service->create($this->userId));
    }

    /**
     * Update a single Research in rds system
     *
     * @param integer $researchIndex
     * @param integer $status
     * @param array $portIn
     * @param array $portOut
     * @return string returns the updated object as json
     *
     * @NoAdminRequired
     */
    public function update($researchIndex, $status, $portIn, $portOut) {
        return new JSONResponse($this->service->update( $this->userId, $researchIndex, $portIn, $portOut, $status));
    }

    /**
     * Removes a single research from the user in RDS.
     *
     * @param integer $researchIndex
     * @return string returns the removed object as json
     *
     * @NoAdminRequired
     */
    public function remove($researchIndex) {
        return new JSONResponse($this->service->remove($researchIndex, $this->userId));
    }
}