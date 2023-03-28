@if(isset($type) && $type=="table")

<tr>
<td>{{$child_category->id}}</td>
<td>
@for($i=1;$i<$loop->depth;$i++)
—
@endfor
{{ $child_category->name }}
</td>

<td>{{$child_category->slug}}</td>
<td>
<div style="max-width: 70px; max-height:70px;overflow:hidden">
<img src="{{ asset($child_category->image) }}" class="img-fluid img-rounded" alt="">
</div>
</td>
<td class="d-flex">
    <a href="{{ route('category.edit', [$child_category->id]) }}" class="btn btn-sm btn-primary mr-1"> <i class="fas fa-edit"></i> </a>
    <form action="{{ route('category.destroy', [$child_category->id]) }}" class="mr-1" method="POST">
        @method('DELETE')
        @csrf 
        <button type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
    </form>
    
</td>
    
    
    </tr>
    @if ($child_category->categories)
            @foreach ($child_category->categories as $childCategory)
                @include('admin.category.child_category', ['child_category' => $childCategory,'type'=>"table"])
            @endforeach
        
    @endif

@elseif(isset($type) && $type=='radio')
    <input type="radio" class="form-check-input" value="{{ $child_category->id }}" name="category" > {{ $child_category->name }}
@if ($child_category->categories)
    <ul>
        @foreach ($child_category->categories as $childCategory)
            @include('admin.category.child_category', ['child_category' => $childCategory, 'type'=>'radio'])
        @endforeach
    </ul>
@endif

@elseif(isset($type) && $type=='checkbox')
    <input type="checkbox" class="form-check-input" value="{{ $child_category->id }}" name="category[]" > {{ $child_category->name }}
@if ($child_category->categories)
    <ul>
        @foreach ($child_category->categories as $childCategory)
            @include('admin.category.child_category', ['child_category' => $childCategory, 'type'=>'checkbox'])
        @endforeach
    </ul>
@endif

@else
    <option value="{{ $child_category->id }}">
    @for($i=1;$i<$loop->depth;$i++)
    &nbsp &nbsp &nbsp
    @endfor
    {{ $child_category->name }}
    @if ($child_category->categories)
            @foreach ($child_category->categories as $childCategory)
                @include('admin.category.child_category', ['child_category' => $childCategory])
            @endforeach
        
    @endif
@endif
