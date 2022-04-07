<input type="text" id="{{ $name }}" name="{{ $name }}" class="form-control"
       @if($errors->has($name)) class="text-neg" @endif
       @if(isset($placeholder)) placeholder="{{$placeholder}}" @endif
       @if(isset($tabindex)) tabindex="{{$tabindex}}" @endif
       @if(isset($model) || old($name)) value="{{ old($name) ? old($name) : $model->$name}}" @endif
       @if(isset($required)) required @endif />
