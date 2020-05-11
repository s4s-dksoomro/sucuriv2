@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    


<div class="row">
                <div class="col-xs-12">
                    <h2>Reports</h2>
                    <h2 class="subtitle">Generate reports according to your wishes in desired format.</h2>


<div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>SELECT DESIRED VALUES FOR REPORTS</h3>
<br><br>
@if(isset($message))
    <div class="alert alert-success" role="alert">
        {{  $message }}
    </div>
    @endif
            {{-- For Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
           
              <div class="container">
                      <form method="get" action="reportsss">
                        <div class="form-group">
                          <label for="url">EMAIL:</label>
                          <input type="email" class="form-control"  placeholder="ENTER EMAIL" name="email" style="width: 70%">
                        </div>
                        <div class="form-group">
                          <label for="inputState">Enter day, week, month (default)</label>
                          <select style="width: 70%" id="inputState" class="form-control" name="period">
                            <option selected value="" disabled="disabled">Choose Period</option>
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="inputState">Enter Format</label>
                          <select style="width: 70%" id="inputState" class="form-control" name="format">
                            <option selected value="" disabled="disabled">Choose Format</option>
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                          </select>
                        </div>
                        <button style="width: 20%" type="submit" onclick="myFunction2()" class="btn btn-success" name="submit">GENERATE</button>
                      </form>
                    </div>   

                    

</div>

          </div>
          
      </div>

    </div>






{{-- <div class="panel panel-default panel-main">
      <div class="panel-body  row">
          <div class="col-lg-8">
          <div  class="setting-title" ><h3>
Pseudo IPv4   </h3>




<p>
Adds an IPv4 header to requests when a client is using IPv6, but the server only supports IPv4.
</p>



 


</div>

          </div>
         
      </div>

    </div> --}}



           
           
          </div>
      </div>

    </div>










</div>
</div>

@stop

@section('javascript') 
    
@endsection
