<?php

namespace App\Repositories;

class RevisionRepository
{
    /**
     * @var \App\Models\Revision
     */
    private $revision;

    public function __construct(\App\Models\Revision $revision)
    {
        $this->revision = $revision;
    }

    public function getRevisions($revisionableId, $revisionableType)
    {
        return $this->revision
                 ->where("revisionable_id", $revisionableId)
                 ->where("revisionable_type", $revisionableType)
                 ->get();
    }
    
    public function getRevision($revisionId)
    {
        return $this->revision->find($revisionId);
    }
}
