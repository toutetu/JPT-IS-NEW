@extends('layouts.app')

@section('content')
<div class="container" style="max-width:820px;">
  <h1 class="h4 mb-3">担任のクラス割当</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('admin.assign.homeroom.store') }}" class="card card-body">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">担任（教師）</label>
        <!-- <select name="teacher_id" class="form-select">
          @foreach($teachers as $t)
            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->email }})</option>
          @endforeach
        </select> -->
        <select name="teacher_id" class="form-select">
            @foreach($teachers as $t)
                <option value="{{ $t->id }}"
                @selected(old('teacher_id', $selectedTeacherId ?? null) == $t->id)>
                {{ $t->name }} ({{ $t->email }})
                </option>
            @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">クラス</label>
        <select name="classroom_id" class="form-select">
          @foreach($classes as $c)
            <option value="{{ $c->id }}" 
            @selected(old('classroom_id', $currentHomeroom->classroom_id ?? null) == $c->id)>
            {{ $c->cname }}
            </option>
          @endforeach
        </select>
        <small class="text-muted">
          現在の担当: {{ $currentHomeroom ? $currentHomeroom->classroom_name : '未設定' }}
        </small>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">戻る</a>
      <button type="submit" class="btn btn-primary">割り当てる</button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.querySelector('select[name="teacher_id"]');
    
    if (teacherSelect) {
        teacherSelect.addEventListener('change', function() {
            const selectedTeacherId = this.value;
            if (selectedTeacherId) {
                // 現在のURLにteacher_idパラメータを追加してリロード
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('teacher_id', selectedTeacherId);
                window.location.href = currentUrl.toString();
            }
        });
    }
});
</script>
@endsection
