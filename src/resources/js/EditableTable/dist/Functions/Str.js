"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Str = void 0;
const Pluralizer_1 = require("./Pluralizer");
class Str {
}
exports.Str = Str;
Str.toHeadline = (text) => {
    return text
        .toString()
        .toLowerCase()
        .split(/[\s_]+/) // Split by spaces or underscores
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
Str.makeId = (n) => {
    if (!n)
        return '';
    if (typeof n == 'string')
        return n;
    const strPad = String(n).padStart(6, '0');
    return `#${strPad.substring(0, 3)}.${strPad.substring(3)}`;
};
Str.pluralize = (singular, count) => {
    return Pluralizer_1.Pluralize.pluralize(singular, count);
};
Str.humanReadable = (count) => {
    // const suffix = count === 1 ? 'item' : 'items'
    if (count >= 1000000) {
        return `${(count / 1000000).toFixed(1).replace(/\.0$/, '')}M`;
    }
    else if (count >= 1000) {
        return `${(count / 1000).toFixed(1).replace(/\.0$/, '')}k`;
    }
    return count.toString();
};
// Usage
// console.log(Str.toHeadline('hello_world_test')) // "Hello World Test"
// console.log(Str.toHeadline('another example_string')) // "Another Example String"
//# sourceMappingURL=Str.js.map