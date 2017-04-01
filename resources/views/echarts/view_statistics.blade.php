<div id="view_statistics" style="width: 100%;height:300px;"></div>
<script type="text/javascript">
    $(function () {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('view_statistics'));

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '堆叠区域图'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: <?=json_encode($name_list)?>
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: <?=json_encode($time_index_list)?>
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                    @foreach($container as $key => $val)
                {
                    name: '{{$key}}',
                    type: 'line',
                    stack: '总量',
                    areaStyle: {normal: {}},
                    data: {{json_encode($val)}}
                },
                @endforeach

            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    });

</script>