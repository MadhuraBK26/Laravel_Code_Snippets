<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RevisionController extends Controller
{
    /**
     * @var \App\Repositories\RevisionRepository
     */
    private $revisionRepository;

    /**
     * @var \App\Services\RevisionService
     */
    private $revisionService;

    public function __construct(\App\Services\RevisionService $revisionService, \App\Repositories\RevisionRepository $revisionRepository)
    {
        $this->revisionService = $revisionService;
        $this->revisionRepository = $revisionRepository;
    }

    public function showRevisions($revisionId = null)
    {
        $revision = $this->revisionRepository->getRevision($revisionId);
        $revisions = $this->revisionService->getRevisionsGroupedByDate($revision->revisionable_id, $revision->revisionable_type);
        return view('admin.revisions.showRevisions', compact('revisions'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
