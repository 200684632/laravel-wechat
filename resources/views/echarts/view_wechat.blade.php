<div id="view_wechat" style="width: 100%;height:300px;"></div>
<script type="text/javascript">
    $(function () {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('view_wechat'));

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '统计折形图'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:<?=json_encode($name_list)?>
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                data: <?=json_encode($time_index_list)?>
            },
            yAxis: {
                type: 'value'
            },
            series: [
                    @foreach($container as $key => $val)
                {
                    name: '{{$key}}',
                    type: 'line',
                    step: '总量',
                    data: {{json_encode($val)}}
                },
                    @endforeach
            ]
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    });

</script>