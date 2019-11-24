<?php 
  $qtdLabelsValidos = 0;
  for ($i = 0; $i < count($model->labels); $i++) {
    if(strpos($model->labels[$i], "Unknown") === false){
      $qtdLabelsValidos++;  
    }  
  }
   
  $qtdValuesValidos = 0;
  for ($i = 0; $i < count($model->values); $i++) {
    if($model->values[$i] !== 0){
      $qtdValuesValidos++;  
    }  
  }  

  //dd($model->values);
?>




<script type="text/javascript">
    
    function drawChart()
    {
      // Define the chart to be drawn.
      var data = new google.visualization.arrayToDataTable([
                  
          [@for ($i = 0; $i < count($model->labels); $i++)            
              @if ( strpos($model->labels[$i], "Unknown") === false)
                {!! $model->labels[$i] !!}
                @if ($i+1 < $qtdLabelsValidos)
                  , 
                @endif
              @endif
          @endfor],
          @for ($i = 0; $i < count($model->values); $i++)            
              @if( $model->values[$i] !== 0)
                [ {!! $model->values[$i] !!} ]
                @if( $i+1 < $qtdValuesValidos)
                  ,
                @endif
              @endif
          @endfor
         
      ]);
  

    
      //data.setRowProperty(2, 'style', 'background: green');
    
      // Set chart options
      var options = {
        allowHtml:true,
        allowCollapse:true
      };

      
        
      // Instantiate and draw the chart.
   
      var chart = new google.visualization.OrgChart(document.getElementById("{{ $model->id }}"));
 

      chart.draw(data, options);
    
    }


google.charts.setOnLoadCallback(drawChart);

</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
