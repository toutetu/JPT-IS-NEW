@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px;">
  <h1 class="h4 mb-3 text-danger">
    <i class="fas fa-exclamation-triangle"></i> クラス削除確認
  </h1>

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card border-danger">
    <div class="card-header bg-danger text-white">
      <strong>削除するクラス情報</strong>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th style="width: 150px;">クラスID</th>
          <td>{{ $classroom->id }}</td>
        </tr>
        <tr>
          <th>学年</th>
          <td>{{ $classroom->grade_name }}</td>
        </tr>
        <tr>
          <th>クラス名</th>
          <td><strong>{{ $classroom->name }}</strong></td>
        </tr>
        <tr>
          <th>在籍生徒数</th>
          <td>
            {{ $classroom->active_student_count }}名
            @if($classroom->active_student_count > 0)
              <span class="badge bg-warning text-dark">在籍中</span>
            @endif
          </td>
        </tr>
        <tr>
          <th>担任</th>
          <td>
            {{ $classroom->current_teacher?->name ?? '未設定' }}
            @if($classroom->current_teacher)
              <span class="badge bg-info">担当中</span>
            @endif
          </td>
        </tr>
      </table>

      @if($classroom->active_student_count > 0)
        <div class="alert alert-warning">
          <strong><i class="fas fa-exclamation-circle"></i> 警告</strong><br>
          このクラスには現在{{ $classroom->active_student_count }}名の生徒が在籍しています。<br>
          <strong>削除できません。</strong>先に生徒の在籍を変更してください。
        </div>
      @endif

      @if($classroom->current_teacher)
        <div class="alert alert-warning">
          <strong><i class="fas fa-exclamation-circle"></i> 警告</strong><br>
          このクラスには現在担任（{{ $classroom->current_teacher->name }}）が割り当てられています。<br>
          <strong>削除できません。</strong>先に担任割当を変更してください。
        </div>
      @endif

      @if($classroom->active_student_count == 0 && !$classroom->current_teacher)
        <div class="alert alert-danger">
          <strong><i class="fas fa-skull-crossbones"></i> 削除の影響</strong>
          <ul class="mb-0 mt-2">
            <li>このクラスに関連する<strong>過去の在籍記録</strong>が削除されます</li>
            <li>このクラスに関連する<strong>過去の担任記録</strong>が削除されます</li>
            <li><strong>この操作は取り消すことができません</strong></li>
          </ul>
        </div>
      @endif
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left"></i> キャンセル
    </a>
    
    @if($classroom->active_student_count == 0 && !$classroom->current_teacher)
      <form method="post" action="{{ route('admin.classrooms.destroy', $classroom->id) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="btn btn-danger" 
                onclick="return confirm('本当にこのクラスを削除しますか？\nこの操作は取り消すことができません。')">
          <i class="fas fa-trash"></i> 削除を実行
        </button>
      </form>
    @else
      <button type="button" class="btn btn-danger" disabled>
        <i class="fas fa-ban"></i> 削除できません
      </button>
    @endif
  </div>
</div>
@endsection

