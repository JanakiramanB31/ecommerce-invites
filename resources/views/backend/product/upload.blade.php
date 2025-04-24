@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Products Via Files(.csv)</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.uploadcsvdata')}}">
        {{csrf_field()}}

        <div class="form-group">
          <label for="inputCsv" class="col-form-label">Upload CSV <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="csv" accept=".csv" value="{{old('csv')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('csv')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    // $('#lfm').filemanager();
    $('#lfm').filemanager('file', {
        prefix: '/laravel-filemanager',
    });

    // Optional: Only allow CSV
    // $('#lfm').on('click', function () {
    //   window.SetUrl = function (url) {
    //     let finalUrl = typeof url === 'string' ? url : url.url;

    //     if (typeof finalUrl === 'string' && finalUrl.endsWith('.csv')) {
    //         $('#thumbnail').val(finalUrl);
    //     } else {
    //         alert('Please select a CSV file.');
    //     }
    //   };
    // });

</script>
@endpush