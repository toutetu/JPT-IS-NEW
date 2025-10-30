@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
  <h1 class="h4 mb-3">新規クラス作成</h1>

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('admin.classrooms.store') }}" class="card card-body">
    @csrf
    
    <div class="mb-3">
      <label for="grade_id" class="form-label">学年 <span class="text-danger">*</span></label>
      <select name="grade_id" id="grade_id" class="form-select" required>
        <option value="">-- 学年を選択 --</option>
        @foreach($grades as $grade)
          <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
            {{ $grade->name }}
          </option>
        @endforeach
      </select>
      <small class="text-muted">クラスが属する学年を選択してください。</small>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">クラス名 <span class="text-danger">*</span></label>
      <input type="text" 
             name="name" 
             id="name" 
             class="form-control" 
             value="{{ old('name') }}" 
             placeholder="例: 1年A組、2年B組"
             required>
      <small class="text-muted">わかりやすいクラス名を入力してください。</small>
    </div>

    <div class="alert alert-info">
      <strong><i class="fas fa-info-circle"></i> 注意事項</strong>
      <ul class="mb-0 mt-2">
        <li>同じ学年内で同じクラス名は登録できません</li>
        <li>クラス名には学年情報を含めることを推奨します（例: 1年A組）</li>
      </ul>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> キャンセル
      </a>
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> クラスを作成
      </button>
    </div>
  </form>
</div>
@endsection

