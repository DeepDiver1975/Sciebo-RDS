<?php
namespace OCA\RDS\Db;

use \OCA\RDS\Db\Research;
use \OCA\RDS\Service\NotFoundException;

class ResearchMapper {
    private $rdsURL = 'https://sciebords-dev.uni-muenster.de/research';

    public function __construct() {

    }

    public function insert( $userId ) {
        $curl = curl_init( $this->rdsURL . '/user/' . $userId );
        $options = [CURLOPT_RETURNTRANSFER => true];
        curl_setopt_array( $curl, $options );
        curl_setopt( $curl, CURLOPT_POST, TRUE );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, [] );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );

        $request = curl_exec( $curl );
        $response = json_decode( $request, true );
        $httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        $info = curl_getinfo( $curl );

        curl_close( $curl );

        if ( $httpcode >= 300 ) {
            throw new NotFoundException( json_encode( [
                'http_code'=>$httpcode,
                'json_error_message'=>json_last_error_msg(),
                'curl_error_message'=>$info,
                'response'=>$request
            ] ) );
        }

        $conn = new Research();
        $conn->setUserId( $response['userId'] );
        $conn->setStatus( $response['status'] );
        $conn->setResearchId( $response['researchId'] );
        $conn->setResearchIndex( $response['researchIndex'] );
        $conn->setPortIn( $response['portIn'] );
        $conn->setPortOut( $response['portOut'] );

        return $conn;
    }

    public function update( $conn ) {
        /*$curl = curl_init( $this->rdsURL . '/user/' . $conn->getUserId() . '/research/' . $conn->getResearchIndex() );
        $options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => 'PUT'];
        curl_setopt_array( $curl, $options );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $conn->jsonSerialize() ) );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );

        $response = json_decode( curl_exec( $curl ) );
        $httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        $info = curl_getinfo( $curl );

        curl_close( $curl );

        if ( $httpcode >= 300 ) {
            throw new NotFoundException( json_encode( [
                'http_code'=>$httpcode,
                'json_error_message'=>json_last_error_msg(),
                'curl_error_message'=>$info
            ] ) );
        }
        */
        $current = $this->find( $conn->researchIndex, $conn->userId ).jsonSerialize();
        $new = $conn.jsonSerialize();

        $remove = array_diff_assoc( $current, $new );
        $add = array_diff_assoc( $new, $current );

        $this->addPort( $add );
        $this->removePort( $remove );
        $this->setStatus( $conn );

        return $conn;
    }

    private function addPort( $conn ) {
        foreach ( $conn->portIn as $port ) {
            if ( !$this->addPortIn( $conn->researchIndex, $conn->userId, $port ) ) {
                throw new Exception( 'Could not add import' );
            }
        }

        foreach ( $conn->portOut as $port ) {
            if ( !$this->addPortOut( $conn->researchIndex, $conn->userId, $port ) ) {
                throw new Exception( 'Could not add export' );
            }
        }
    }

    private function removePort( $conn ) {
        foreach ( $conn->portIn as $port ) {
            if ( !$this->removePortIn( $conn->researchIndex, $conn->userId, $port ) ) {
                throw new Exception( 'Could not add import' );
            }
        }

        foreach ( $conn->portOut as $port ) {
            if ( !$this->removePortOut( $conn->researchIndex, $conn->userId, $port ) ) {
                throw new Exception( 'Could not add export' );
            }
        }
    }

    private function removePortIn( $researchIndex, $userId, $port ) {
        return internalRemovePort( $conn, 'imports' );
    }

    private function removePortOut( $researchIndex, $userId, $port ) {
        return internalRemovePort( $conn, 'exports' );
    }

    private function addPortIn( $researchIndex, $userId, $port ) {
        return internalAddPort( $researchIndex, $userId, $port, 'imports' );
    }

    private function addPortOut( $researchIndex, $userId, $port ) {
        return internalAddPort( $researchIndex, $userId, $port, 'exports' );
    }

    private function internalRemovePort( $researchIndex, $userId, $port, $where ) {
        # TODO: implements me
        throw new Exception( 'Not implemented' );
    }

    private function internalAddPort( $researchIndex, $userId, $port, $where ) {
        # TODO: implements me
        throw new Exception( 'Not implemented' );
    }

    private function setStatus( $conn ) {
        # TODO: implements me
        throw new Exception( 'Not implemented' );
    }

    public function delete( $researchIndex, $userId ) {
        $conn = $this->find( $researchIndex, $userId );

        $curl = curl_init( $this->rdsURL . '/user/' . $userId . '/research/' . $researchIndex );
        $options = [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => 'DELETE'];
        curl_setopt_array( $curl, $options );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );

        $request = curl_exec( $curl );
        $response = json_decode( $request );
        $httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        $info = curl_getinfo( $curl );

        curl_close( $curl );

        if ( $httpcode >= 300 ) {
            throw new NotFoundException( json_encode( [
                'http_code'=>$httpcode,
                'json_error_message'=>json_last_error_msg(),
                'curl_error_message'=>$info,
                'content'=>$request
            ] ) );
        }

        return $conn;
    }

    public function find( $researchIndex, $userId ) {
        $curl = curl_init( $this->rdsURL . '/user/' . $userId . '/research/' . $researchIndex );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        $result = curl_exec( $curl );
        $response = json_decode( $result, true );
        $httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        $info = curl_getinfo( $curl );

        curl_close( $curl );

        if ( $httpcode >= 300 ) {
            throw new NotFoundException( json_encode( [
                'http_code'=>$httpcode,
                'json_error_message'=>json_last_error_msg(),
                'curl_error_message'=>$info
            ] ) );
        }

        $conn = new Research();
        $conn->setUserId( $response['userId'] );
        $conn->setStatus( $response['status'] );
        $conn->setResearchId( $response['researchId'] );
        $conn->setResearchIndex( $response['researchIndex'] );
        $conn->setPortIn( $response['portIn'] );
        $conn->setPortOut( $response['portOut'] );

        return $conn;
    }

    public function findAll( $userId ) {
        $curl = curl_init( $this->rdsURL . '/user/' . $userId );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        $result = curl_exec( $curl );
        $response = json_decode( $result, true );
        $httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        $info = curl_getinfo( $curl );

        curl_close( $curl );

        if ( $httpcode >= 300 ) {
            throw new NotFoundException( json_encode( [
                'http_code'=>$httpcode,
                'json_error_message'=>json_last_error_msg(),
                'curl_error_message'=>$info
            ] ) );
        }

        $result = [];

        foreach ( $response as $rdsConn ) {
            $conn = new Research();
            $conn->setUserId( $rdsConn['userId'] );
            $conn->setStatus( $rdsConn['status'] );
            $conn->setResearchId( $rdsConn['researchId'] );
            $conn->setResearchIndex( $rdsConn['researchIndex'] );
            $conn->setPortIn( $rdsConn['portIn'] );
            $conn->setPortOut( $rdsConn['portOut'] );

            $result[] = $conn;
        }

        return $result;
    }

}