<section xmlns="http://www.w3.org/1999/html">
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
    <section>
        <div class="category"> {{$key}} </div>
    </section>
    @foreach($feedbackTransactionData as $feedbackData)
        @foreach ($projectData['categorydetails'][$key] as $det)
            <section class="form_3 form-horizontal">
                <div class="row {{ $det['is_mandatory'] ? "bg-info" : ""}}">
                    <div class="form_top col-md-3">
                        {{$det['metrics_name']}} {{ $det['is_mandatory'] ? "*" : "" }}
                    </div>
                    <input type="hidden" class="form-control" value="{{$det['metrics_id']}}" readonly>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?php
                            $textAreaAttributes = null;
                            $comment = null;
                            $selectedExpectationId = null;
                            if (array_key_exists($det['metrics_id'], $feedbackTransactionData)) {
                                $comment = $feedbackTransactionData[$det['metrics_id']]['comments'];
                                $selectedExpectationId = $feedbackTransactionData[$det['metrics_id']]['expectation_id'];
                            }
                            $expectations = ['' => 'Select your ratings'] + $projectData['expectation'];
                            if ($det['is_mandatory']) {
                                $textAreaAttributes = 'data-bv-message="" required data-bv-notempty-message="" pattern="" data-bv-regexp-message="" data-bv-stringlength="true" data-bv-stringlength-min=""';
                                $expectations = $projectData['expectation'];
                            }
                            ?>
                        <!--     <textarea {{ $textAreaAttributes }} class="form-control col-md-3" cols="5" rows="5"
                                      name="comment_{{$det['metrics_id']}}" placeholder="Comments and Examples"
                                    >{{ $comment }}</textarea> -->
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
                                    class="form-control col-md-3" cols="5" rows="5"
                                    name="comment_{{$det['metrics_id']}}" placeholder="Comments and Examples"
                                    commentTextValidation="true">{{ $comment }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="select2-selection__rendered">
                {!! Form::select('expectation_'.$det['metrics_id'], $expectations , $selectedExpectationId, array('class' => 'form-control')) !!} <span class="pmRating">{{$selectedExpectationId}} </span>
                        </div>
                    </div>

                </div>
            </section>
        @endforeach
        <?php break; ?>
    @endforeach
@endforeach
<div class="centered">
    <button type="submit" class="btn btn-default" name="sub">Submit</button>
    <button type="button" class="btn btn-default" onclick="window.location='{{ route("report") }}'">Cancel</button>
</div>