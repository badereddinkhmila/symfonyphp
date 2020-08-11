function executeQuery(){   
    $.ajax({  
       url:        '/dashboard/patients/{{id}}/data',  
       type:       'POST',   
       dataType:   'json',  
       async:      true,  

       success: function(param, status) {
           var tension=[];
           var oxygene=[];
          for(i = 0; i < param.length; i++) {
             prs=Object.values(param[i]);
             tension.push(prs[0]);  
          }
         $('#val').text(tension[tension.length - 1]);
         $('#val').show();   
         
         var chartData = {
         labels: ["S", "M", "T", "W", "T", "F", "S"],
         datasets: [{
             data: tension ,
             label: "Courbe de tension",
             borderColor:'rgb(0,0,255)'
             }]};

         var chLine = document.getElementById("blood");
         if (chLine) {
         new Chart(chLine, {
         type: 'line',
         data: chartData,
             options: {
                 scales: {
                     yAxes: [{
                         ticks: {
                             beginAtZero: false
                                 }
         }]
         },
         legend: {
         display: true
         }}});}
       },  
       error : function(xhr, textStatus, errorThrown) {
          alert('Ajax request failed.');  
       }  
    });
    updateCall();}    
    
     $(document).ready(function() {
     executeQuery();
     });

     function updateCall(){
     setInterval(function(){executeQuery()}, 100000000);
     }
