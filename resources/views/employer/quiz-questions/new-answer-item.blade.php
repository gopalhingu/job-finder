<div class="row answers-container">
    <div class="col-md-8">
        <div class="form-group">
            <input type="hidden" name="answer_ids[]" class="form-control" />
            <input type="text" name="answer_titles[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group text-center">
            <input type="hidden" class="answer" name="answers[]" value="0">
            @if($type == 'checkbox')
            <input type="checkbox" name="answers2[]" class="minimal">
            @else
            <input type="radio" name="answers2[]" class="minimal">
            @endif
        </div>
    </div>
    <div class="col-md-2 text-center">
        <div class="form-group">
            <i class="fa fa-trash text-red remove-answer" data-id=""></i>
        </div>
    </div>
</div>