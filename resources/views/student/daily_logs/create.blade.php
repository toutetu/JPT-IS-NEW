@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3">連絡帳の提出</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- 所属情報 --}}
  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <div><strong>所属クラス</strong>：{{ $classroomName ?? '未設定' }}</div>
        <div><strong>担任</strong>：{{ $homeroomTeacherName ?? '未割り当て' }}</div>
      </div>
    </div>
  </div>

  <form method="post" action="{{ route('student.daily_logs.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">対象日（前登校日）</label>
      <input type="date" name="target_date" class="form-control" value="{{ old('target_date', $targetDate) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">体調</label>
      <div class="d-flex align-items-center">
        <small class="text-muted me-2">悪い</small>
        <div class="btn-group" role="group" aria-label="体調スコア">
          @for($i=1;$i<=5;$i++)
            <input type="radio" class="btn-check" name="health_score" id="health_{{$i}}" value="{{ $i }}" 
                   @checked(old('health_score', 3) == $i)>
            <label class="btn btn-outline-primary" for="health_{{$i}}">{{ $i }}</label>
          @endfor
        </div>
        <small class="text-muted ms-2">良い</small>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">メンタル</label>
      <div class="d-flex align-items-center">
        <small class="text-muted me-2">悪い</small>
        <div class="btn-group" role="group" aria-label="メンタルスコア">
          @for($i=1;$i<=5;$i++)
            <input type="radio" class="btn-check" name="mental_score" id="mental_{{$i}}" value="{{ $i }}" 
                   @checked(old('mental_score', 3) == $i)>
            <label class="btn btn-outline-success" for="mental_{{$i}}">{{ $i }}</label>
          @endfor
        </div>
        <small class="text-muted ms-2">良い</small>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">本文</label>
      <textarea name="body" rows="6" class="form-control" placeholder="今日の様子を記入してください。">{{ old('body') }}</textarea>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('student.daily_logs.index') }}" class="btn btn-outline-secondary">戻る</a>
      <button type="submit" class="btn btn-primary">提出する</button>
    </div>
  </form>
</div>
@endsection
