const createColorCols = (context, totalColor = "#E615A3", normalColor = "#00E3A8") => {
    const labels = context.chart.data.labels;
    const currentLabel = labels[context.dataIndex];
    return currentLabel.toLowerCase() === "total" ? totalColor : normalColor;
};
