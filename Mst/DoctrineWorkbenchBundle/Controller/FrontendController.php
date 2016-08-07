<?php

namespace Mst\DoctrineWorkbenchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\File;

class FrontendController extends Controller
{
    /**
     * Render index page
     * 
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('MstDoctrineWorkbenchBundle:Frontend:index.html.twig');
    }
    
    /**
     * Return app views file
     * 
     * @return BinaryFileResponse
     */
    public function viewsAction()
    {
        $file = new File($this->getParameter('kernel.root_dir').'/../web/bundles/mstdoctrineworkbench/doctrine-workbench/views/index.html');
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $response->getFile()->getFileName());
 
        return $response;
    }
    
    /**
     * Show all saved schemas
     * 
     * @return JsonResponse
     */
    public function listAction()
    {
        try {
            $schemas = $this->get('doctrine_workbench.repository')->findAll();
            
            return $this->returnJsonSuccessResponse($schemas);
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getTraceAsString());
                
            return $this->returnJsonFailResponse($e->getMessage());
        }
    }
    
    /**
     * Get schema by id
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        $result = $this->returnJsonFailResponse('Error: format data.');
        $content = $request->getContent();
        
        if ($this->get('doctrine_workbench.view_validator')->isValidJson($content)) {
            $contentData = json_decode($content);
            $result = $this->returnJsonFailResponse('Schema not found.');
            $schema = $this->get('doctrine_workbench.repository')->find($contentData->id);
            
            if (false !== $schema) {
                $result = $this->returnJsonSuccessResponse($schema);
            }
        }

        return $result;
    }
    
    /**
     * Save the schema
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function saveAction(Request $request)
    {
        $result = $this->returnJsonFailResponse('Error: format data.');
        $content = $request->getContent();
        
        if ($this->get('doctrine_workbench.view_validator')->isValidSaveData($content)) {
            $contentData = json_decode($content);
            $result = $this->returnJsonFailResponse('Error: schema not saved.');
            $isSaved = $this->get('doctrine_workbench.repository')->save(array(
                'name' => $contentData->name,
                'zoom' => $contentData->zoom,
                'schema' => json_encode($contentData->schema)
            ));
            
            if ($isSaved) {
                $result = $this->returnJsonSuccessResponse(array('message' => 'Schema saved.'));
            }
        }
        
        return $result;
    }
    
    /**
     * Delete a schema by id
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $result = $this->returnJsonFailResponse('Error: format data.');
        $content = $request->getContent();
        
        if ($this->get('doctrine_workbench.view_validator')->isValidJson($content)) {
            $contentData = json_decode($content);
            $result = $this->returnJsonFailResponse('Error: schema not deleted.');
            $isDeleted = $this->get('doctrine_workbench.repository')->delete($contentData->id);
            
            if ($isDeleted) {
                $result = $this->returnJsonSuccessResponse(array('message' => 'Schema deleted.'));
            }
        }

        return $result;
    }
    
    /**
     * Proccess schema to Doctrine
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function proccessAction(Request $request)
    {
        $result = $this->returnJsonFailResponse('Error: format data.');
        $dataJson = $request->getContent();
        
        if ($this->get('doctrine_workbench.view_validator')->isValidProccessData($dataJson)) {            
            try {                
                $dir = $this->getParameter('kernel.root_dir') . '\..\src';
                $exportType = $this->getParameter('doctrine_workbench.export');
                $this->get('doctrine_workbench.doctrine_transformer')->handleJsonData($dataJson, $exportType, $dir);

                $result = $this->returnJsonSuccessResponse(array(
                    'message' => 'Success'
                ));
            } catch (\Exception $e) {
                $logger = $this->get('logger');
                $logger->error($e->getTraceAsString());
                
                $result = $this->returnJsonFailResponse($e->getMessage());
            }
        }
        
        return $result;
    }
    
    /**
     * Return success response
     * 
     * @param mixed $data
     * 
     * @return JsonResponse
     */
    public function returnJsonSuccessResponse($data)
    {
        return $this->returnJsonResponse(array('success' => true, 'data' => $data));
    }

    /**
     * Return fail response
     * 
     * @param string $message
     * 
     * @return JsonResponse
     */
    public function returnJsonFailResponse($message)
    {
        return $this->returnJsonResponse(array('success' => false, 'message' => $message));
    }
    
    /**
     * Return JsonResponse
     * 
     * @param array $data
     * 
     * @return JsonResponse
     */
    public function returnJsonResponse(array $data)
    {
        return new JsonResponse($data);
    }
}
