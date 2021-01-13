const charting = async (id, gateway) => {

    function line_charts(element_id,titre){
        let options = {
            series: [{
                name:`${titre}`,
                data:[]
            }],
            chart: {
                id: `${titre}`,
                type: 'line',
                height: 400,
                events:{
                    beforeMount: function () {
                        document.getElementById( `loader`).classList.add('d-none');
                        document.getElementById(`${element_id}`).classList.remove('d-none');
                    }
                },
                zoom: {
                    type: 'x',
                    enabled: true,
                    autoScaleYaxis: true
                },
                grid: {
                    row: {
                        colors: ['#7c7c7c', '#f7f7f7'], // takes an array which will be repeated on columns
                        opacity: 0.8
                    },
                },
                toolbar: {
                    autoSelected: 'zoom',
                    show: true
                }
            },

            theme: {
                monochrome: {
                    enabled: true,
                    color: '#f9a3a4',
                    shadeTo: 'light',
                    shadeIntensity: 0.5
                },
                mode:'light',
                palette: 'palette4'
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
                show: true,
                curve: 'smooth',
                lineCap: 'round',
                width: 2,
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type:'solid',
                opacity: 0.8,
            },
            markers: {
                size: 0
            },
            tooltip:{
                theme: 'dark',
                show:true,
                x:{
                    format:"dd/MM/yyyy HH:mm:ss"
                }
            },
            xaxis: {
                type: 'datetime',
            },
        };

        let chart = new ApexCharts(document.querySelector(`#${element_id}`), options);
        chart.render();
        return chart;}

        function jaugechart (element_id,titre,unite) {
            var options = {
                chart: {
                    height: 220,
                    type: "radialBar",
                },
                series: [],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: "70%",
                            background: "#474753"
                        },
                        startAngle: -100,
                        endAngle: 100,
                        track: {
                            dropShadow: {
                                startAngle: -100,
                                endAngle: 100,
                                enabled: true,
                                top: 2,
                                left: 0,
                                blur: 4,
                                opacity: 1
                            }
                        },
                        dataLabels: {
                            name: {
                                offsetY: -10,
                                color: "#fff",
                                fontSize: "13px"
                            },
                            value: {
                                color: "#fff",
                                fontSize: "30px",
                                show: true,
                                formatter: function (val) {
                                    return val + `${unite}`
                                }
                            }
                        }
                    }
                },
                fill: {
                    colors: [function({ value, seriesIndex, w }) {
                        if (value >= 35 && value <= 38) {
                            return '#1bf510'
                        } else if(value <= 35) {
                            return '#1f52ec'
                        }else{
                            return '#d92e3e'
                        }
                    }]
                },
                stroke: {
                    lineCap: "round"
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
                theme:{
                  mode:'light',
                  palette: 'palette5'
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
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                    }
                },
                xaxis: {
                    type: 'datetime',
                }
            };
            let brushLine = new ApexCharts(document.querySelector(`#${element_id}`), optionsLine);
            brushLine.render();
            return brushLine;
        }

        /******** Glycemie **************/
        let line_gly=line_charts('gly-line2','Glycémie');
        let gly_max=jaugechart('glypie','Glycémie maximale',' C°');
        let gly_min=jaugechart('glypie2','Glycémie minimale',' C°');
        let brush_gly=brush_line('gly-line','brush_gly','Glycémie');
        /********** temperature ************/
        let line_temp=line_charts('temp-line2','Température');
        let temperature_max=jaugechart('temppie','Température maximale',' C°');
        let temperature_min=jaugechart('temppie2','Température minimale',' C°');
        let brush_temp=brush_line('temp-line','brush_temp','Température');
        /**************** O2 *********************/
        let line_oxy=line_charts('oxy-line2','Oxygéne');
        let oxy_max=jaugechart('oxypie','Niveau O2 maximale',' C°');
        let oxy_min=jaugechart('oxypie2','Niveau O2 minimale',' C°');
        let brush_oxy=brush_line('oxy-line','brush_oxygene','Oxygéne');
        /**************** tension *********************/
        let line_tension=line_charts('tension-line2','Tension');
        let tension_max=jaugechart('tensionpie','Tension maximale',' C°');
        let tension_min=jaugechart('tensionpie2','Tension minimale',' C°');
        let brush_tension=brush_line('tension-line','brush_tension','Tension');
        /**************** Heart beat *********************/
        let line_hb=line_charts('heart-line2','Battement de Coeur');
        let hb_max=jaugechart('heartpie','Battement maximale',' BPM');
        let hb_min=jaugechart('heartpie2','Battement minimale',' BPM');
        let brush_hb=brush_line('heart-line','brush_hb','Battement de Coeur');
        /**************** poids *********************/
        let line_poids=line_charts('poids-line2','Poids');
        let poids_max=jaugechart('poidspie','Poids maximale',' KG');
        let poids_min=jaugechart('poidspie2','Poids minimale',' KG');
        let brush_poids=brush_line('poids-line','brush_poids','Poids');
    await fetch("/dashboard/iot/"+id+" ")
        .then(res => res.json())
        .then(time_series_Data => {
                let data = time_series_Data;
                let first=data[0]
                let last =data[data.length - 1];
                let num =parseInt(last[1]/100);
                temperature_max.updateSeries([num],true);
                temperature_min.updateSeries([first[1]/100],true);

                line_temp.updateSeries([{
                    data: data
                }]);

                brush_temp.updateSeries([{
                    data: data
                }]);

                gly_max.updateSeries([num],true);
                gly_min.updateSeries([first[1]/100],true);

                line_gly.updateSeries([{
                    data: data
                }]);

                brush_gly.updateSeries([{
                    data: data
                }]);

                oxy_max.updateSeries([num],true);
                oxy_min.updateSeries([first[1]/100],true);

                line_oxy.updateSeries([{
                    data: data
                }]);

                brush_oxy.updateSeries([{
                    data: data
                }]);

                tension_max.updateSeries([num],true);
                tension_min.updateSeries([first[1]/100],true);

                line_tension.updateSeries([{
                    data: data
                }]);

                brush_tension.updateSeries([{
                    data: data
                }]);

                hb_max.updateSeries([num],true);
                hb_min.updateSeries([first[1]/100],true);

                line_hb.updateSeries([{
                    data: data
                }]);

                brush_hb.updateSeries([{
                    data: data
                }]);
                poids_max.updateSeries([num],true);
                poids_min.updateSeries([first[1]/100],true);

                line_poids.updateSeries([{
                    data: data
                }]);

                brush_poids.updateSeries([{
                    data: data
                }]);
        });
    $(function($){
        $("#live").click(function(){
            const hub ='http://localhost:3000/.well-known/mercure?topic='+encodeURIComponent(`http://avcdocteur.com/${gateway}`);
            const eventSource = new EventSource(hub, {withCredentials: true});
            eventSource.onopen=()=>{
            }
            let live_jauge=jaugechart('#live_pie','Température actuelle',' C°');
            $('#live_pie').removeClass('d-none');
            $('#chartpie').addClass('d-none');
            $('#chartpie2').addClass('d-none');
            $('#chart-line').addClass('d-none');
            line_chart.updateSeries([{
                data: []
            }]);
            eventSource.onerror=ev => {
                console.log('error');
                console.log(ev)
            }
            eventSource.onmessage = (e) => {
               let stream_data=JSON.parse(e.data);
               let data_array = [stream_data[8]*1000,stream_data[3]];
                line_chart.appendData([{
                    data:[data_array]
                }]);

                live_jauge.updateSeries([stream_data[3]/100],true);
            }
            $("#stop").click(function(){
                eventSource.close()
                $('#live_pie').addClass('d-none');
                $('#chartpie').removeClass('d-none');
                $('#chartpie2').removeClass('d-none');
            });
        });

        $('#refresh').click(function(){
            let start=$('#datepicker-from').val();
            let end=$('#datepicker-to').val();
            let period=JSON.stringify({'from':start,'to':end});
            $.post("/dashboard/iot/"+id+"",period,function(response) {
                //refreshing charts with new data
                let last =response[response.length - 1];
                let num =parseInt(last[1]/100);
                temperature_max.updateSeries([num],true);
                temperature_min.updateSeries([num],true)
                line_chart.updateSeries([{
                    data: response
                }]);
                /*brushLine.updateSeries([{
                    data: response
                }])*/
            })
        });
    });
}
