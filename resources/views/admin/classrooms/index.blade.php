@extends('layouts.app')

@section('content')
<div class="container">
  
  @if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  
  @if (session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">クラス管理</h1>
    <div>
      <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> 新規クラス作成
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>学年</th>
            <th>クラス名</th>
            <th>在籍生徒数</th>
            <th>担任</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($classrooms as $classroom)
            <tr>
              <td>{{ $classroom->id }}</td>
              <td>{{ $classroom->grade->name }}</td>
              <td>{{ $classroom->name }}</td>
              <td>
                @php
                  $activeCount = $classroom->enrollments()->where('is_active', true)->count();
                @endphp
                {{ $activeCount }}名
              </td>
              <td>
                @php
                  $currentTeacher = $classroom->homeroomAssignments()
                    ->whereNull('until_date')
                    ->with('teacher')
                    ->first();
                @endphp
                {{ $currentTeacher?->teacher?->name ?? '未設定' }}
              </td>
              <td>
                <a href="{{ route('admin.classrooms.delete', $classroom->id) }}" 
                   class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i> 削除
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">クラスが登録されていません。</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $classrooms->links('pagination::bootstrap-4') }}
  </div>

  <div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left"></i> ユーザー管理に戻る
    </a>
  </div>
</div>
@endsection

