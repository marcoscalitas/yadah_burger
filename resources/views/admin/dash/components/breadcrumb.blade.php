 <div class="page-header">
     <div class="page-block">
         <ul class="breadcrumb">
             <li class="breadcrumb-item">
                 <a href="{{ route('admin.index') }}">Home</a>
             </li>
             @isset($items)
                 @foreach ($items as $item)
                     <li class="breadcrumb-item">
                         @if (isset($item['url']))
                             <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                         @else
                             <span aria-current="page">{{ $item['label'] }}</span>
                         @endif
                     </li>
                 @endforeach
             @endisset
         </ul>
         <div class="page-header-title">
             <h2 class="mb-0">{{ $title ?? '' }}</h2>
         </div>
     </div>
 </div>
