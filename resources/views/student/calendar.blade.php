@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">提出カレンダー</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <!-- 年月ナビゲーション -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('student.calendar', ['year' => $year, 'month' => $month - 1]) }}" 
       class="btn btn-outline-secondary">
      ← 前月
    </a>
    
    <h2 class="h5 mb-0">{{ $year }}年{{ $month }}月</h2>
    
    <a href="{{ route('student.calendar', ['year' => $year, 'month' => $month + 1]) }}" 
       class="btn btn-outline-secondary">
      次月 →
    </a>
  </div>

  <!-- カレンダー -->
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th class="text-center" style="width: 14.28%">月</th>
              <th class="text-center" style="width: 14.28%">火</th>
              <th class="text-center" style="width: 14.28%">水</th>
              <th class="text-center" style="width: 14.28%">木</th>
              <th class="text-center" style="width: 14.28%">金</th>
              <th class="text-center" style="width: 14.28%">土</th>
              <th class="text-center" style="width: 14.28%">日</th>
            </tr>
          </thead>
          <tbody>
            @php
              $firstDay = $startDate->copy()->startOfWeek();
              $lastDay = $endDate->copy()->endOfWeek();
              $current = $firstDay->copy();
            @endphp
            
            @while($current->lte($lastDay))
              <tr>
                @for($i = 0; $i < 7; $i++)
                  @php
                    $isCurrentMonth = $current->month == $month;
                    $isSubmitted = in_array($current->toDateString(), $submittedDates);
                    $isToday = $current->isToday();
                  @endphp
                  
                  <td class="text-center position-relative" 
                      style="height: 80px; vertical-align: top; 
                             @if(!$isCurrentMonth) background-color: #f8f9fa; @endif
                             @if($isToday) border: 2px solid #007bff; @endif">
                    
                    <!-- 日付 -->
                    <div class="position-absolute top-0 start-0 p-2">
                      <span class="@if(!$isCurrentMonth) text-muted @elseif($isToday) fw-bold text-primary @else fw-bold @endif">
                        {{ $current->day }}
                      </span>
                    </div>
                    
                    <!-- 提出済みマーク -->
                    @if($isSubmitted && $isCurrentMonth)
                      <div class="position-absolute bottom-0 start-0 end-0 text-center">
                        <span class="text-danger fw-bold" style="border: 2px solid #dc3545; border-radius: 50%; padding: 2px 6px; font-size: 0.8rem;">
                          済
                        </span>
                      </div>
                    @endif
                  </td>
                  
                  @php $current->addDay(); @endphp
                @endfor
              </tr>
            @endwhile
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 凡例 -->
  <div class="mt-4">
    <div class="d-flex align-items-center gap-4">
      <div class="d-flex align-items-center">
        <span class="text-danger fw-bold me-2" style="border: 2px solid #dc3545; border-radius: 50%; padding: 2px 6px; font-size: 0.8rem;">済</span>
        <small>提出済み</small>
      </div>
      <div class="d-flex align-items-center">
        <div class="border border-primary me-2" style="width: 20px; height: 20px;"></div>
        <small>前登校日</small>
      </div>
    </div>
  </div>

  <!-- アクションボタン -->
  <div class="mt-4 d-flex gap-2">
    <a href="{{ route('student.daily_logs.index') }}" class="btn btn-outline-secondary">
      マイ連絡帳一覧
    </a>
    <a href="{{ route('student.daily_logs.create') }}" class="btn btn-primary">
      新規提出
    </a>
  </div>
</div>
@endsection
