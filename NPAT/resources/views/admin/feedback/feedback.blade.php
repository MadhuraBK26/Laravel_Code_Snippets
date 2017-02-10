@if(isset($message))
    <div class="alert alert-warning">{{$message}}</div>
@endif

@if(isset($projectData))
<section>
    <div class="row form_3">
        <div class="col-md-4 form_top1 ">
            Metrics
        </div>
        <div class="col-md-4 form_top1 ">
            Comments
        </div>
        <div class="col-md-4 form_top1 " style="text-align: center">
            Rating
        </div>
    </div>
</section>


<div class="category"></div>
@foreach($projectData['categorydetails'] as $key=>$value)
    @if(!in_array($user->navigator_designation_id, config('custom.designation_group4')) )
        <section>
            <div class="category">
                {{$key}}</div>
        </section>
    @endif
    @foreach ($projectData['categorydetails'][$key] as $det)
        <section class="form_3 form-horizontal">
            <div class="row
                            @if ($det['is_mandatory'])
                    bg-info
                    @endif">
                <div class="form_top col-md-4">
                    {{$det['metrics_name']}}
                    @if ($det['is_mandatory'])*

                    @endif
                </div>
                <input type="hidden" class="form-control" value="{{$det['metrics_id']}}" readonly>

                <div class="col-md-6">
                    <div class="form-group">
                                    <textarea
                                            @if ($det['is_mandatory'])
                                            data-bv-message=""
                                            data-bv-notempty-message=""
                                            pattern="" data-bv-regexp-message=""
                                            data-bv-stringlength="true" data-bv-stringlength-min=""
                                            mandatory=true
                                            @else
                                            ratingValidation=true
                                            @endif
                                            class="form-control col-md-3"
                                            name="comment_{{$det['metrics_id']}}" placeholder="Comments and Examples"
                                            type="hidden" commentTextValidation=true></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="select2-selection__rendered">
                        @if ($det['is_mandatory'])
                            {!! Form::select('expectation_'.$det['metrics_id'], $projectData['expectation'], null, array('class' => 'form-control')) !!}
                           

                        @else
                            {!! Form::select('expectation_'.$det['metrics_id'],['' => 'Select your ratings'] + $projectData['expectation'], null, array('class' => 'form-control')) !!}
                        @endif

                    </div>
                </div>
            </div>
        </section>
    @endforeach
@endforeach
<div class="centered">
    <button type="submit" class="btn btn-default addFeedback-btn" name="sub">Submit</button>
    <button type="button" class="btn btn-default" onclick="window.location='{{ route("dashboard") }}'">Cancel</button>
</div>

@endif
