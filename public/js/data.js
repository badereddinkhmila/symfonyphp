const charting = async (id, gateway) => {

    
    var locale =
    {
            "name": "fr",
            "options": {
              "months": [
                "janvier",
                "février",
                "mars",
                "avril",
                "mai",
                "juin",
                "juillet",
                "août",
                "septembre",
                "octobre",
                "novembre",
                "décembre"
              ],
              "shortMonths": [
                "janv.",
                "févr.",
                "mars",
                "avr.",
                "mai",
                "juin",
                "juill.",
                "août",
                "sept.",
                "oct.",
                "nov.",
                "déc."
              ],
              "days": [
                "dimanche",
                "lundi",
                "mardi",
                "mercredi",
                "jeudi",
                "vendredi",
                "samedi"
              ],
              "shortDays": ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
              "toolbar": {
                "exportToSVG": "Télécharger au format SVG",
                "exportToPNG": "Télécharger au format PNG",
                "exportToCSV": "Télécharger au format CSV",
                "menu": "Menu",
                "selection": "Sélection",
                "selectionZoom": "Sélection et zoom",
                "zoomIn": "Zoomer",
                "zoomOut": "Dézoomer",
                "pan": "Navigation",
                "reset": "Réinitialiser le zoom"
              }
            }
        };
    
    function line_charts(element_id,titre,v_title,extra_annotations){
        let options = {
            series: [],
            chart: {
                id: `${titre}`,
                type: 'line',
                height: 400,
                locales:[locale],
                defaultLocale: 'fr',
                zoom: {
                    type: 'x',
                    enabled: true,
                    autoScaleYaxis: true
                },    

                grid: {
                    xaxis:{
                        lines:{
                            show:true
                        }
                    },
                    yaxis:{
                        lines:{
                            show:true
                        }
                    },
                    row: {
                        colors: ['#e5e5e5', 'transparent'],
                        opacity: 0.5
                    },
                    column: {
                        colors: ['#f8f8f8', 'transparent'],
                    },
                },
                toolbar: {
                    autoSelected: 'zoom',
                    show: true
                }
            },
            annotations: extra_annotations,
            legend: {
                show:true,
                onItemClick: {
                    toggleDataSeries: true
                },
                onItemHover: {
                    highlightDataSeries: true
                },
                position: 'bottom',
                horizontalAlign: 'center'
            },

             
            theme: {
                mode: 'light',
                palette: 'palette1'
            },
            animations: {
                enabled: true,
                easing: 'linear',
                speed: 2000,
                animateGradually: {
                    enabled: true,
                    delay: 300
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 600
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type:'solid',
                opacity: 0.7,
            },
            markers: {
                size: 2
            },
            tooltip:{
                theme: 'light',
                show:true,
                x:{
                    format:"dd/MM/yyyy HH:mm:ss"
                }
            },
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                show: true,
                showAlways: true,
                opposite: false,
                forceNiceScale: true,
                labels: {
                    offsetX: -2,
                    offsetY: -5
                },
                tooltip: {
                    enabled: true
                },
                axisBorder: {
                    show: true,
                    color: '#78909C',
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: '#78909C',
                    width: 6,
                    offsetX: 0,
                    offsetY: 0
                },
                title: {
                    text: `${v_title}`,
                    rotate: -90,
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        fontFamily: 'Arvo, serif',
                        fontSize: '15px',
                        fontWeight: 600,
                        cssClass: 'apexcharts-yaxis-title',
                    },
                },
            }
        };

        let chart = new ApexCharts(document.querySelector(`#${element_id}`), options);
        chart.render();
        return chart;}

    const max = 300
    function valueToPercent (value) {    
    return (value * 100) / max
    }
    function percentToValue (value) {
        return (value * max) / 100
    }    
    
    function jaugechart (element_id,titre,unite,jauge_options,scaled) { 
            var options = {
                chart: {
                    height: 400,
                    type: "radialBar",
                    sparkline:{
                        enabled:true
                    },
                    toolbar: {
                        autoSelected: 'zoom',
                        show: true
                    }
                },
                series: [],
                plotOptions: {
                    radialBar: { 
                        hollow: {
                            margin: 20,
                            size: "60%",
                            background: "#264653"
                        },
                        startAngle: -120,
                        endAngle: 120,
                        track: {
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                blur: 4,
                                opacity: 0.35
                            }
                        },
                        dataLabels: {
                            name: {
                                offsetY: -10,
                                color: "#fff",
                                fontSize: "15px"
                            },
                            value: {
                                color: "#fff",
                                fontSize: "25px",
                                show: true,
                                formatter: scaled ? function (val) {
                                    return percentToValue(val) + `${unite}`
                                }: function (val) {
                                    return val + `${unite}`
                                } 
                            }
                        }
                    }
                },
                
                fill: jauge_options,
                legend: {
                    show:true,

                    onItemClick: {
                        toggleDataSeries: true
                    },
                    onItemHover: {
                        highlightDataSeries: true
                    },
                    position: 'top',
                    horizontalAlign: 'left'
                },
                labels: [`${titre}`]
            };
            let jauge =new ApexCharts(document.querySelector(`#${element_id}`), options)
            jauge.render();
            return jauge;
        }

    function brush_line(element_id,titre,target) {
        let optionsLine = {
                series: [{
                    data:[]
                }],
                chart: {
                    id: `${titre}`,
                    height: 130,
                    type: 'area',
                    brush: {
                        target: `${target}`,
                        enabled: true
                    },
                    selection: {
                        enabled: true,
                    },
                },
                theme: {
                    mode: 'light',
                    palette: 'palette1'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                    }
                },
                xaxis: {
                    type: 'datetime',
                },
                yaxis:{
                    labels:{
                        show:false
                    }
                }
            };
            let brushLine = new ApexCharts(document.querySelector(`#${element_id}`), optionsLine);
            brushLine.render();
            return brushLine;
        }
    

        /*** Extra options ***/
        let temp_options=[];
        let tension_options=[];
        let oxygen_options=[];
        let glucose_options=[];
        let poids_options=[];
        temp_options['annotations']= {
            yaxis: [{
              y: 10,
              borderColor: '#999',
              label: {
                show: true,
                text: 'Temperature Normale',
                style: {
                  color: "#fff",
                  background: '#00E396'
                }
              }
            }],
            xaxis: [{
              x: new Date('now').getTime(),
              borderColor: '#999',
              yAxisIndex: 0,
              label: {
                show: true,
                text: "Aujourd'hui",
                style: {
                  color: "#fff",
                  background: '#775DD0'
                }
              }
            }]
        }
        temp_options['fill']={
            colors: [function({ value, seriesIndex, w }) {
                if (value < 35) {
                    return '#2a9d8f'
                }
                if (value < 36.5 &&value >= 35) {
                    return '#95d5b2'
                } else if(value >= 36.5 && value<=37.5) {
                    return '#40916c'
                } else if(value > 37.5 && value < 40) {
                    return '#f4a261'
                } else if(value >=40) {
                    return '#e76f51'
                }
            }]
        }
        
        oxygen_options['fill']={
            colors: [function({ value, seriesIndex, w }) {
                if(value >= 95 && value<=100) {
                    return '#40916c'
                } else if(value >= 88 && value < 95) {
                    return '#e9c46a'
                } else if(value < 88) {
                    return '#e76f51'
                }
            }]
        }
        
        glucose_options['fill']={
            colors: [function({ value, seriesIndex, w }) {
                if(value >= valueToPercent(70) && value<=valueToPercent(99)) {
                    return '#40916c'
                }
                if( value>valueToPercent(140) || value<valueToPercent(70)) {
                    return '#e76f51'
                }
                if(value <= valueToPercent(140) && value > valueToPercent(99)) {
                    return '#e9c46a'
                }
            }]
        }

        tension_options['annotations']={
            yaxis: [{
              y: 120,
              borderColor: '#999',
              label: {
                show: true,
                text: 'Systolique Normale',
                style: {
                  color: "#fff",
                  background: '#00E390'
                }
              }
            },{
                y: 80,
                borderColor: '#999',
                label: {
                  show: true,
                  text: 'Diastolique Normale',
                  style: {
                    color: "#fff",
                    background: '#45a164'
                  }
                }
              },{
                y: 60,
                y2:100,
                fillColor:'#B4D6C1',
                borderColor: '#999',
                label: {
                  show: true,
                  text: 'Battement Normale',
                  style: {
                    color: "#fff",
                    background: '#B4D6C1'
                  }
                }
              }],
        }

        oxygen_options['annotations']={
            yaxis: [{
                y: 95,
                y1:100,
                borderColor: '#999',
                label: {
                  show: true,
                  text: 'Spo2 Ideale',
                  style: {
                    color: "#fff",
                    background: '#45a164'
                  }
                }
              },{
                y: 88,
                y1:95,
                fillColor:'#B4D6C1',
                borderColor: '#999',
                label: {
                  show: true,
                  text: 'Spo2 Normale',
                  style: {
                    color: "#fff",
                    background: '#B4D6C1'
                  }
                }
              }],
        }

        glucose_options['annotations']={
            yaxis: [{
              y: 77,
              y1:99,
              borderColor: '#999',
              label: {
                show: true,
                text: 'Mesure normale à jeun',
                style: {
                  color: "#fff",
                  background: '#00E390'
                }
              }
            },{
                y: 140,
                borderColor: '#999',
                label: {
                  show: true,
                  text: '2 heures après repas',
                  style: {
                    color: "#fff",
                    background: '#45a164'
                  }
                }
              },{
                y: 80,
                y1:130,
                fillColor:'#B4D6C1',
                borderColor: '#999',
                label: {
                  show: true,
                  text: 'Mesure à jeun pour diabète',
                  style: {
                    color: "#fff",
                    background: '#00E390'
                  }
                }
              },{
                y: 180,
                borderColor: '#999',
                label: {
                  show: true,
                  text: '2 heures après repas pur diabète',
                  style: {
                    color: "#fff",
                    background: '#45a164'
                  }
                }
              }],
        }
        tension_options['diastolic']={
            colors: [function({ value, seriesIndex, w }) {
                if (value > valueToPercent(80) && value <= valueToPercent(89)) {
                    return '#e9c46a'
                } else if(value <= valueToPercent(80)) {
                    return '#2a9d8f'
                } else if(value >=valueToPercent(90)) {
                    return '#f4a261'
                }else if(value=>valueToPercent(120)){
                    return '#e76f51'
                }
            }]
        }
        tension_options['systolic']={
            colors: [function({ value, seriesIndex, w }) {
                if (value >= valueToPercent(120) && value <= valueToPercent(129)) {
                    return '#e9c46a'
                } else if(value >= valueToPercent(130) && value <= valueToPercent(139)){
                    return '#f4a261'
                }
                else if(value <= valueToPercent(120)) {
                    return '#2a9d8f'
                } else if(value >=valueToPercent(140)) {
                    return '#e76f51'
                }else if(value=>valueToPercent(180)){
                    return '#f94144'
                }
            }]
        }
        
        poids_options['bmi']={
            colors: [function({ value, seriesIndex, w }) {
                if (value < 18) {
                    return '#e9c46a'
                } else if(value >= 18.5 && value < 23) {
                    return '#2a9d8f'
                } else if(value>=23 && value<30) {
                    return '#f4a261'
                }else if(value=>30.0){
                    return '#e76f51'
                }
            }]
        }
        /******** Glycemie **************/

        let line_gly=line_charts('gly-line2','Glycémie','Glyémies',glucose_options['annotations']);
        let gly_max = jaugechart('glypie','Glycémie maximale','Mg_dl', glucose_options['fill'], true);
        let gly_min = jaugechart('glypie2','Glycémie minimale','Mg_dl',glucose_options['fill'], true);
        let brush_gly=brush_line('gly-line','brush_gly','Glycémie');

        /********** temperature ************/

        let line_temp=line_charts('temp-line2','Température','Temperature',temp_options['annotations']);
        let temperature_max=jaugechart('temppie','Température maximale',' C°',temp_options['fill'],false);
        let temperature_min=jaugechart('temppie2','Température minimale',' C°',temp_options['fill'],false);
        let brush_temp=brush_line('temp-line','brush_temp','Température');

        /**************** O2 *********************/

        let line_oxy=line_charts('oxy-line2','Oxygéne','Oxygéne',oxygen_options['annotations']);
        let oxy_max=jaugechart('oxypie','Niveau O2 maximale',' %',oxygen_options['fill'],false);
        let oxy_min=jaugechart('oxypie2','Niveau O2 minimale',' %',oxygen_options['fill'],false);
        let brush_oxy=brush_line('oxy-line','brush_oxygene','Oxygéne');

        /**************** tension *********************/

        let line_tension=line_charts('tension-line2','Tension','Tension',tension_options['annotations']);
        let systolic=jaugechart('tensionpie','Systolique',' mmHg',tension_options['systolic'],true);
        let diastolic=jaugechart('tensionpie2','Diastolique',' mmHg',tension_options['diastolic'],true);
        let brush_tension=brush_line('tension-line','brush_tension','Tension');

        /**************** poids *********************/
        let line_poids=line_charts('poids-line2','Poids','Poids',temp_options['annotations']);
        let poids_max=jaugechart('poidspie','BMI maximale',' %',temp_options['fill'],false);
        let poids_min=jaugechart('poidspie2','BMI minimale',' %',temp_options['fill'],false);
        let brush_poids=brush_line('poids-line','brush_poids','Poids');

    await fetch("/dashboard/iot/"+id+"")
        .then(res => res.json())
        .then(stream_data => {
            let temperature=stream_data['temperature'];
            let oxygen=stream_data['oxygen'];
            let weight=stream_data['weight'];
            let glucose=stream_data['glucose'];
            let bp =stream_data['bp'];
            
                                                  
            line_temp.updateSeries([
                {name:'Temperature',data: temperature['temperature']}
            ]);

            brush_temp.updateSeries([{
                data: temperature['temperature']
            }]);

            line_gly.updateSeries([
                {name:'Mg_dl',data: glucose['mg_dl']},
                {name:'Mmol_l',data: glucose['mmol_l']}
            ]);

            brush_gly.updateSeries([{
                data: glucose['mg_dl']
            }]);

            line_oxy.updateSeries([
                {name:'Pulse',data: oxygen['pulse']},
                {name:'Spo2',data:oxygen['spo2']}
            ]);

            brush_oxy.updateSeries([{
                data:oxygen['spo2']
            }]);

            line_tension.updateSeries([
                {name:'Diastolique',data: bp['diastolic']},
                {name:'BPM',data: bp['pulse']},
                {name:'Systolique',data: bp['systolic']}
            ]);

            
            line_poids.updateSeries([
                {name:'BMI',data: weight['bmi']},
                {name:'Masse grasse',data: weight['bodyfat']},
                {name:'Poids',data:weight['weight']}
            ]);
            
            temperature_max.updateSeries([(temperature['max_min'])[0]],true);
            temperature_min.updateSeries([(temperature['max_min'])[1]],true);
            gly_max.updateSeries([valueToPercent((glucose['max_min'])[0])],true);
            gly_min.updateSeries([valueToPercent((glucose['max_min'])[1])],true);

            oxy_max.updateSeries([(oxygen['max_min'])[0]],true);
            oxy_min.updateSeries([(oxygen['max_min'])[1]],true);

            diastolic.updateSeries([valueToPercent((bp['max_min'])[0])],true);
            systolic.updateSeries([valueToPercent((bp['max_min'])[1])],true);

            poids_max.updateSeries([(weight['max_min'])[0]],true);
            poids_min.updateSeries([(weight['max_min'])[1]],true);
            
            brush_poids.updateSeries([{
                data: weight['bmi']
            }]);
            
            brush_tension.updateSeries([{
                data: bp['pulse']
            }]);

        });

    $(function($){
        $("#live").click(function(){
            const hub ='http://localhost:4000/.well-known/mercure?topic='+encodeURIComponent(`http://avcdocteur.com/${gateway}/live`);
            const LiveEventSource = new EventSource(hub, {withCredentials: true});
            LiveEventSource.onopen=()=>{
                $("#start_stream").toast('show');
            }
            
            line_temp.updateSeries([{data: []}]);
            line_gly.updateSeries([{data: []}]);
            line_oxy.updateSeries([{data: []}]);
            line_tension.updateSeries([{
                name:'Diastolique', data:[]
                },{name:'Pulse', data:[]},
                {name:'Systolique', data:[]
                }]);
            line_poids.updateSeries([{data: []}]);

            LiveEventSource.onmessage = (e) => {
                let stream_data=JSON.parse(e.data);
                let message = stream_data[1];
                switch (stream_data[0]) {
                    case 'avc/glucose':
                        line_gly.appendData([{
                        name:'Mg_dl', data:[[message['collect_time']*1000,message['mg_dl']]]
                        },{name:'Mmol_l', data:[[message['collect_time']*1000,message['mmol_l']]]
                        }]);
                    break;
                    case 'avc/temperature':
                        line_temp.appendData([{
                        name:'Temperature', data:[[message['collect_time']*1000,message['temperature']]]
                        }]);
                    break;
                    case 'avc/oxygen':
                        line_oxy.appendData([{
                        name:'Pulse', data:[(message['collect_time'])*1000,(message['pulse']).toFixed(2)]
                        },{name:'Spo2', data:[(message['collect_time'])*1000,(message['spo2']).toFixed(2)]
                            }]);
                    break;
                    case 'avc/blood_pressure':
                        line_tension.appendData([{
                        data:[[(message['collect_time'])*1000,message['diastolic']]]
                        },{data:[[(message['collect_time'])*1000,message['pulse']]]},
                        {data:[[(message['collect_time'])*1000,message['systolic']]]
                        }]);
                        systolic.updateSeries([valueToPercent((message['systolic']).toFixed(2))],true);
                        diastolic.updateSeries([valueToPercent((message['diastolic']).toFixed(2))],true);   
                    break;
                    case 'avc/weight':
                        line_poids.appendData([{
                        name:'BMI', data:[[(message['collect_time'])*1000,(message['bmi']).toFixed(2)]]
                        },{name:'Masse Grasse', data:[[(message['collect_time'])*1000,(message['bodyfat']).toFixed(2)]]},
                        {name:'Poids', data:[[(message['collect_time'])*1000,(message['weight']).toFixed(2)]]
                        }]);
                        break;                                                            
                   default:
                        break;
               }
            } 


            $("#stop").click(function(){
                try {
                    LiveEventSource.close();
                    $("#stop_stream").toast('show');    
                } catch (error) {
                    console.log(error);
                }
            });

            $(window).on('beforeunload', function(){
                LiveEventSource.close();
            });
        });

        $('#refresh').click(function(){
            let start=$('#datepicker-from').val();
            let end=$('#datepicker-to').val();
            let period=JSON.stringify({'from':start,'to':end});
            $.post("/dashboard/iot/"+id+"",period,function(response) {
                
            })
                /*line_temp.updateSeries([]);
                line_gly.updateSeries([]);
                line_oxy.updateSeries([]);
                line_tension.updateSeries([]);
                line_poids.updateSeries([]);
                brush_gly.updateSeries([]);
                brush_temp.updateSeries([]);
                brush_oxy.updateSeries([]);
                brush_tension.updateSeries([]);
                brush_poids.updateSeries([]);*/
                $('.modal').modal('show');
            const hub ='http://localhost:4000/.well-known/mercure?topic='+encodeURIComponent(`http://avcdocteur.com/${gateway}/update`);
            const updateEventSource = new EventSource(hub, {withCredentials: true});
            updateEventSource.onopen=(e)=>{
                $("#update_stream").toast('show');
            }
            updateEventSource.onmessage = (e) => {
                $('.modal').modal('hide') 
                let stream_data=JSON.parse(e.data);
                let temperature=stream_data['temperature'];
                let oxygen=stream_data['oxygen'];
                let weight=stream_data['weight'];
                let glucose=stream_data['glucose'];
                let bp =stream_data['bp'];
                if((temperature['temperature']).length > 0){
                    line_temp.updateSeries([
                        {name:'Temperature', data: temperature['temperature']}
                    ]).then(e=>{$('.modal').modal('hide')});
                    
                    brush_temp.updateSeries([{
                        data: temperature['temperature']
                    }]);

                    temperature_max.updateSeries([(temperature['max_min'])[0]],true);
                    temperature_min.updateSeries([(temperature['max_min'])[1]],true);
                }
                if((glucose['mg_dl']).length > 0){
                    line_gly.updateSeries([
                        {name:'Mg_dl',data: glucose['mg_dl']},
                        {name:'Mmol_l',data: glucose['mmol_l']}
                    ]);
                    brush_gly.updateSeries([{
                        data: glucose['mg_dl']
                    }]);
                    gly_max.updateSeries([valueToPercent((glucose['max_min'])[0])],true);
                    gly_min.updateSeries([valueToPercent((glucose['max_min'])[1])],true);
                }
    
                if((oxygen['spo2']).length > 0){
                    line_oxy.updateSeries([
                        {name:'Pulse',data: oxygen['pulse']},
                        {name:'Spo2',data:oxygen['spo2']}
                    ]);
                    brush_oxy.updateSeries([{
                        data:oxygen['spo2']
                    }]);
                    oxy_max.updateSeries([(oxygen['max_min'])[0]],true);
                    oxy_min.updateSeries([(oxygen['max_min'])[1]],true);
                }
    
                if((bp['pulse']).length>0){
                    line_tension.updateSeries([
                    {name:'Diastolique',data: bp['diastolic']},
                    {name:'BPM',data: bp['pulse']},
                    {name:'Systolique',data: bp['systolic']}
                ])
                brush_tension.updateSeries([{
                    data: bp['pulse']
                }]);
                diastolic.updateSeries([valueToPercent((bp['max_min'])[0])],true);
                systolic.updateSeries([valueToPercent((bp['max_min'])[1])],true);
                }
                
                if((weight['bmi']).length > 0){
                    line_poids.updateSeries([
                    {name:'BMI',data: weight['bmi']},
                    {name:'Masse grasse',data: weight['bodyfat']},
                    {name:'Poids',data:weight['weight']}
                    ]);
                    brush_poids.updateSeries([{
                        data: weight['bmi']
                    }])

                    poids_max.updateSeries([(weight['max_min'])[0]],true);
                    poids_min.updateSeries([(weight['max_min'])[1]],true);   
                }
                $('.modal').modal('hide')
    
             }
            
            $(window).on('beforeunload', function(){
                updateEventSource.close();
            });
        })


    });
}
