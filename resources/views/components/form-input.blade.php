@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'options' => [], // for select
    'rows' => 3, // for textarea
    'accept' => null, // for file input
    'step' => null, // for number input
    'min' => null,
    'max' => null
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    @if($type === 'select')
        <select name="{{ $name }}" 
                id="{{ $name }}" 
                class="form-select @error($name) is-invalid @enderror"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}>
            <option value="">Pilih {{ $label }}</option>
            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" 
                  id="{{ $name }}" 
                  rows="{{ $rows }}"
                  class="form-control @error($name) is-invalid @enderror"
                  placeholder="{{ $placeholder }}"
                  {{ $required ? 'required' : '' }}
                  {{ $readonly ? 'readonly' : '' }}
                  {{ $disabled ? 'disabled' : '' }}>{{ old($name, $value) }}</textarea>
                  
    @elseif($type === 'file')
        <input type="file" 
               name="{{ $name }}" 
               id="{{ $name }}" 
               class="form-control @error($name) is-invalid @enderror"
               {{ $accept ? "accept=$accept" : '' }}
               {{ $required ? 'required' : '' }}
               {{ $disabled ? 'disabled' : '' }}>
               
    @elseif($type === 'checkbox')
        <div class="form-check">
            <input type="checkbox" 
                   name="{{ $name }}" 
                   id="{{ $name }}" 
                   value="1"
                   class="form-check-input @error($name) is-invalid @enderror"
                   {{ old($name, $value) ? 'checked' : '' }}
                   {{ $disabled ? 'disabled' : '' }}>
            <label class="form-check-label" for="{{ $name }}">
                {{ $label }}
            </label>
        </div>
        
    @else
        <input type="{{ $type }}" 
               name="{{ $name }}" 
               id="{{ $name }}" 
               value="{{ old($name, $value) }}"
               class="form-control @error($name) is-invalid @enderror"
               placeholder="{{ $placeholder }}"
               {{ $step ? "step=$step" : '' }}
               {{ $min ? "min=$min" : '' }}
               {{ $max ? "max=$max" : '' }}
               {{ $required ? 'required' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               {{ $disabled ? 'disabled' : '' }}>
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>