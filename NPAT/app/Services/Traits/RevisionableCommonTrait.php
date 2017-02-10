<?php
namespace App\Services\Traits;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RevisionableTrait
 *
 * @author jeevan
 */
trait RevisionableCommonTrait
{       
    public function getRevisionsLinkAttribute()
    {
        return count($this->revisionHistory) > 0 ? "<a onclick=showRevisionHistoryModal('".route('admin.revisions.show',['revision_id'=>$this->revisionHistory[0]->id])."')>Revisions</a>" : "-";
    }
       
}
