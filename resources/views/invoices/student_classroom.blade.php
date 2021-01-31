@php
  $trimester_amount = floor($student_classroom->amount / 3);
  $trimesters_amounts = [
    $trimester_amount, $trimester_amount, ($student_classroom->amount - $trimester_amount*2)
  ];

  $total = 0;
  $total_trimesters = 0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body class="antialiased">
        <h2>{{ $student_classroom->student->name }} - {{ $student_classroom->classroom->grade . $student_classroom->classroom->symbol }} ({{ $student_classroom->classroom->year }})</h2>
        <table>
          <thead>
            <th>№</th>
            <th>Учебный период</th>
            <th>Задолжность</th>
          </thead>
          <tbody>
            @foreach($trimesters as $k => $trimester)
              @php
                $trimesters_names = ['first', 'second', 'third'];
                $start = $trimesters_names[$trimester - 1] . '_trimester_start_date';
                $end = $trimesters_names[$trimester - 1] . '_trimester_end_date';

                $trimeset_sum = $trimesters_amounts[$trimester-1];
                $payments_sum = 0;

                foreach($student_classroom->payments as $payment) {
                  if($payment->trimester === $trimester) {
                    $payments_sum += $payment->amount;
                  }
                }

                $total += $payments_sum;
                $total_trimesters += $trimeset_sum;
              @endphp
            <tr>
              <td>{{ $k+1 }}</td>
              <td>{{ $student_classroom->classroom->school_year[$start]->format('d.m.Y') }} - {{ $student_classroom->classroom->school_year[$end]->format('d.m.Y') }}</td>
              <td>{{ number_format($trimeset_sum - $payments_sum) }}/{{number_format($trimeset_sum)}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <p>Общая сумма задолжности: {{number_format($total_trimesters - $total)}}</p>
    </body>
</html>
