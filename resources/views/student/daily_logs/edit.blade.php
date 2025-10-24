@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3">連絡帳の修正</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('student.daily_logs.update', $log->id) }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">対象日</label>
      <input type="date" name="target_date" class="form-control" value="{{ old('target_date', $log->target_date) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">体調</label>
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">悪い</small>
        <div class="btn-group" role="group" aria-label="体調スコア">
          @for($i=1;$i<=5;$i++)
            <input type="radio" class="btn-check" name="health_score" id="health_{{$i}}" value="{{ $i }}" 
                   @checked(old('health_score', $log->health_score) == $i)>
            <label class="btn btn-outline-primary" for="health_{{$i}}">{{ $i }}</label>
          @endfor
        </div>
        <small class="text-muted">良い</small>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">メンタル</label>
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">悪い</small>
        <div class="btn-group" role="group" aria-label="メンタルスコア">
          @for($i=1;$i<=5;$i++)
            <input type="radio" class="btn-check" name="mental_score" id="mental_{{$i}}" value="{{ $i }}" 
                   @checked(old('mental_score', $log->mental_score) == $i)>
            <label class="btn btn-outline-success" for="mental_{{$i}}">{{ $i }}</label>
          @endfor
        </div>
        <small class="text-muted">良い</small>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">本文</label>
      <textarea name="body" rows="6" class="form-control">{{ old('body', $log->body) }}</textarea>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('student.daily_logs.show', $log->id) }}" class="btn btn-outline-secondary">戻る</a>
      <button type="submit" class="btn btn-primary">保存する</button>
    </div>
  </form>
</div>
@endsection

