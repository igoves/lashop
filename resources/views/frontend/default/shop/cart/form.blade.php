<h3>Order Form</h3>
<div class="card">
    <div class="card-body">
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('title', 'Email') }}
            {{ Form::email('email', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('title', 'Phone') }}
            {{ Form::text('phone', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('content', 'Comment') }}
            {{ Form::textArea('comment', null, ['class' => 'form-control', 'rows' => 2]) }}
        </div>
        <div class="form-group">
            {{ Form::submit($submitButtonText, ['class' => 'btn btn-primary btn-block']) }}
        </div>
    </div>
</div>