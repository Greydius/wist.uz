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

        <style>
          @font-face {
              font-family: 'arialuni';
              src: url({{ storage_path('fonts\arialuni.ttf') }}) format("truetype");
              font-weight: 400;
              font-style: normal;
          }
          body {
            font-family: 'arialuni';
            padding: 45px 60px;
          }
          header {

          }
          .logo {
            width: 60%;
            display: inline-block;
            vertical-align: top;
          }

          .logo img {
            width: 60%;
          }
          .info {
            width: 39%;
            text-align: right;
            display: inline-block;
          }
          h1 {
            text-align:center;
            font-size: 35px;
          }
        </style>
    </head>
    <body class="antialiased">
        <header>
          <div class="logo">
            <img src="{{ asset('/assets/images/invoice-logo.png') }}" alt="">
          </div>
          <div class="info">
            <b>OOO «Westminster Catering»</b>
            <br>
            АТБ Капитал Банк
            <br>
            МФО 01088
            <br>
            Р/с 2020 8000 4052 2936 1001
            <br>
            ИНН 307 431 771
            <br>
            ОКЭД 56290
          </div>
        </header>
        <h1>INVOICE</h1>
        <br>
        <div>
          <div style="display: inline-block; width: 50%; vertical-align: top;">
            Address to _______________
            <br>
            Reference {{ $student_classroom->student->name }}
          </div>
          <div style="display: inline-block; width: 49%;">
            Tashkent
            <br>
            Uzbekistan
            <br>
            Invoice Date
            <br>
            16 November
            <br>
            Invoice Number
            <br>
            INV-{{ $student_classroom->student->id }}
          </div>
        </div>
        <br>
        <table style="width: 100%; border: 1px solid #333;">
          <thead>
            <tr>
              <th style="border-bottom: 1px solid #333; padding: 15px;">Description</th>
              <th style="border-bottom: 1px solid #333; border-left: 1px solid #333; border-right: 1px solid #333; padding: 15px;">Trimester</th>
              <th style="border-bottom: 1px solid #333; padding: 15px;">Amount UZS</th>
            </tr>       
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
                $trimester_names = ['1st', '2nd', '3rd'];
              @endphp
            <tr>
              <td style="border-bottom: 1px solid #333; padding: 15px;">
                UZS Termly Lunch for
                <br>
                {{ $student_classroom->student->name }}
                <br><br>
                № {{ $student_classroom->student->id }}   from {{ $student_classroom->classroom->school_year->first_trimester_start_date->format('d/m/Y') }}
              </td>
              <td style="border-bottom: 1px solid #333; border-left: 1px solid #333; border-right: 1px solid #333; padding: 15px;">{{ $trimester_names[$trimester-1] }}</td>
              <td style="border-bottom: 1px solid #333; padding: 15px;">{{ number_format($trimeset_sum - $payments_sum) }}</td>
            </tr>
            @endforeach
            <tr>
              <td style="padding: 15px;">
                <b>Total</b>
              </td>
              <td style="border-left: 1px solid #333; border-right: 1px solid #333; padding: 15px; padding: 15px;"></td>
              <td style="padding: 15px;">{{number_format($total_trimesters - $total)}}</td>
            </tr>
          </tbody>
        </table>
        <br><br><br>
        <p>
        Due Date / Оплатить до: {{ $student_classroom->classroom->school_year->third_trimester_end_date->format('d.m.Y') }}
        <br><br>
        • Please check your account statement and arrange your payment as soon as possible.
        <br><br>
        Пожалуйста, проверьте баланс вашего счета и произведите оплату в ближайшее время.
        <br><br>
        • A 5% late charge will be levied on all overdue payments.
        <br><br>
        Несвоевременная оплата данного счета повлечет за собой наложение пени
        <br><br>
        в размере 5% от суммы задолженности.
        </p>
    </body>
</html>
