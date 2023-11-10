// function generateColors(numColors) {
//     const baseColors = ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236', '#166a8f', '#00a950', '#58595b', '#8549ba'];
//     const colors = [];
//     for (let i = 0; i <= numColors; i++) {
//         const index = i % baseColors.length;
//         colors.push(baseColors[index]);
//     }
//     return colors;
// }

function generateRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    // Generate a random hex color code
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function generateColors(numColors) {
    const baseColors = [
        '#4dc9f6', '#f67019', '#f53794',
        '#537bc4', '#acc236', '#166a8f',
        '#00a950', '#58595b', '#8549ba'
    ];
    const colors = [];
    for (let i = 0; i < numColors; i++) {
        const index = i % baseColors.length;
        if (i >= baseColors.length) {
            // Generate a new random color
            const newColor = generateRandomColor();
            baseColors.push(newColor);
        }
        colors.push(baseColors[index]);
    }
    return baseColors;
}






const myChart = {};
