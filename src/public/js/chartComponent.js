function generateColors(numColors) {
    const baseColors = ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236', '#166a8f', '#00a950', '#58595b', '#8549ba'];
    const colors = [];

    for (let i = 0; i < numColors; i++) {
        const index = i % baseColors.length; // Lặp lại các màu trong baseColors nếu numColors lớn hơn độ dài của baseColors
        colors.push(baseColors[index]);
    }

    return colors;
}
const myChart = {};
