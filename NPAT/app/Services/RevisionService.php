<?php

namespace App\Services;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RevisionService
 *
 * @author jeevan
 */
class RevisionService
{

    /**
     * @var App\Repositories\RevisionRepository
     */
    private $revisionRepository;

    /**
     * @var StringService
     */
    private $stringService;

    public function __construct(StringService $stringService, \App\Repositories\RevisionRepository $revisionRepository)
    {        
        $this->stringService = $stringService;
        $this->revisionRepository = $revisionRepository;
    }
    
    public function getRevisions($revisionableId, $urlClassName)
    {
        $revisionableType = $this->stringService->getClassNameFromUrl($urlClassName);
        return $this->revisionRepository->getRevisions($revisionableId, $revisionableType);
    }
    
    public function getRevisionsGroupedByDate($revisionableId, $revisionableType)
    {
        $revisionsByDate=[];
        $revisions = $this->revisionRepository->getRevisions($revisionableId, $revisionableType);
        $created_at = null;
        foreach ($revisions as $revision){
            if($created_at != $revision->created_at){
                $created_at = $revision->created_at;
            }
            $revisionsByDate[$created_at->format('Y-m-d')][] = $revision;
        }
        return $revisionsByDate;
    }

}
