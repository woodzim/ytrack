@props(['level' => 0, 'size' => '48px'])

@php
  $level = (int) $level;
  $hexColor = '#be1e2d'; // default danger
  if ($level > 70) {
      $hexColor = '#10b981'; // success
  } elseif ($level > 30) {
      $hexColor = '#f59e0b'; // warning
  }
  
  $innerFontSize = '11px';
  $borderThickness = 5;
  
  if ($size === '40px') {
      $innerFontSize = '10px';
      $borderThickness = 4;
  } elseif ($size === '32px') {
      $innerFontSize = '8px';
      $borderThickness = 3;
  }
@endphp

<div class="battery-circle-container d-inline-flex align-items-center justify-content-center" {{ $attributes }}>
  <div class="battery-circle" 
       style="width: {{ $size }}; height: {{ $size }}; background: radial-gradient(closest-side, white calc(100% - {{ $borderThickness }}px), transparent calc(100% - {{ $borderThickness }}px + 0.5px)), conic-gradient({{ $hexColor }} {{ $level }}%, #e9ecef 0);">
    <span class="battery-value-inner" style="font-size: {{ $innerFontSize }}; color: {{ $hexColor }};">{{ $level }}%</span>
  </div>
</div>

@once
  @push('styles')
    <style>
      .battery-circle {
        position: relative;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .battery-value-inner {
        font-weight: 700;
        z-index: 1;
      }
    </style>
  @endpush
@endonce
