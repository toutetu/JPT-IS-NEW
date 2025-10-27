@extends('layouts.app')

@section('content')
<div class="container" style="max-width:820px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">生徒の在籍割当</h1>
    <a href="{{ route('admin.assign.enrollment.import') }}" class="btn btn-success btn-sm">CSV一括割り当て</a>
  </div>

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

  <form method="post" action="{{ route('admin.assign.enrollment.store') }}" class="card card-body">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">生徒</label>
        <!-- <select name="student_id" class="form-select">
          @foreach($students as $s)
            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
          @endforeach
        </select> -->
        <select name="student_id" class="form-select">
            @foreach($students as $s)
                <option value="{{ $s->id }}"
                @selected(old('student_id', $selectedStudentId ?? null) == $s->id)>
                {{ $s->name }} ({{ $s->email }})
                </option>
            @endforeach
        </select>

      </div>
      <div class="col-md-6">
        <label class="form-label">クラス</label>
        <select name="classroom_id" class="form-select">
          @foreach($classes as $c)
            <option value="{{ $c->id }}" 
            @selected(old('classroom_id', $currentEnrollment->classroom_id ?? null) == $c->id)>
            {{ $c->cname }}
            </option>
          @endforeach
        </select>
        <small class="text-muted">
          現在の在籍: {{ $currentEnrollment ? $currentEnrollment->classroom_name : '未設定' }}
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
    const studentSelect = document.querySelector('select[name="student_id"]');
    
    if (studentSelect) {
        studentSelect.addEventListener('change', function() {
            const selectedStudentId = this.value;
            if (selectedStudentId) {
                // 現在のURLにstudent_idパラメータを追加してリロード
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('student_id', selectedStudentId);
                window.location.href = currentUrl.toString();
            }
        });
    }
});
</script>
@endsection

