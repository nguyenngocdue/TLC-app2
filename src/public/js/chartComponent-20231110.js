
function generateColors(numColors) {
    const colors = [];
    const goldenRatio = 0.618033988749895;
    let hue = Math.random();

    for (let i = 0; i < numColors; i++) {
        hue += goldenRatio;
        hue %= 1;
        const color = hsvToRgb(hue, 0.5, 0.95);
        colors.push(rgbToHex(color));
    }

    return colors;
}

function hsvToRgb(h, s, v) {
    const h_i = Math.floor(h * 6);
    const f = h * 6 - h_i;
    const p = v * (1 - s);
    const q = v * (1 - f * s);
    const t = v * (1 - (1 - f) * s);

    switch (h_i) {
        case 0: return [v, t, p];
        case 1: return [q, v, p];
        case 2: return [p, v, t];
        case 3: return [p, q, v];
        case 4: return [t, p, v];
        case 5: return [v, p, q];
    }
}
function rgbToHex(rgb) {
    const toHex = (value) => {
        const hex = Math.round(value * 255).toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    };
    return '#' + toHex(rgb[0]) + toHex(rgb[1]) + toHex(rgb[2]);
}


const myChart = {};

