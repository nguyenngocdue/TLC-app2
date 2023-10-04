const datasetsCache = {};
const getDataset = (meta, key) => {
    const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
    
    if (!datasetsCache[key]) {
        datasetsCache[key] = {
            labels: meta.labels,
            numbers: meta.numbers,
            backgroundColor: Object.values(COLORS)
        };
    }
    return datasetsCache;
};

// var _datasets = {};
// const chartConfig = {};
// const myChart = {};
var chartData = {}