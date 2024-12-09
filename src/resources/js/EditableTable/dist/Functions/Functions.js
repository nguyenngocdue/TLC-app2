"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getForeignObjects = exports.smartTypeOf = exports.isObject = void 0;
const isObject = (variable) => {
    return variable !== null && typeof variable === 'object' && !Array.isArray(variable);
};
exports.isObject = isObject;
//    Usage
// console.log(isObject({}));             // true
// console.log(isObject(null));           // false
// console.log(isObject([]));             // false
// console.log(isObject("hello"));        // false
// console.log(isObject(123));            // false
// console.log(isObject({ key: "value" })); // true
const smartTypeOf = (variable) => {
    if (Array.isArray(variable))
        return 'array';
    if ((0, exports.isObject)(variable))
        return 'object';
    return typeof variable;
};
exports.smartTypeOf = smartTypeOf;
// export class MyMath {
//     /**
//      * FNV-1a 32-bit hash implementation
//      * @param data The input string to hash
//      * @returns A 32-bit hash as an unsigned integer
//      */
//     static fnv1a_32(data: string): number {
//         // 32-bit FNV prime
//         const fnvPrime = 0x01000193
//         // 32-bit FNV offset basis
//         let hash = 0x811c9dc5
//         // Process each character in the string
//         for (let i = 0; i < data.length; i++) {
//             // XOR the hash with the current character code
//             hash ^= data.charCodeAt(i)
//             // Multiply hash by the FNV prime (modulo 2^32 to keep it 32-bit)
//             hash = (hash * fnvPrime) >>> 0 // Use `>>> 0` for unsigned 32-bit integer
//         }
//         return hash
//     }
//     /**
//      * Creates a fingerprint by concatenating array values and hashing them
//      * @param arrayToCreateFingerprint The input array
//      * @returns The fingerprint as a decimal number
//      */
//     static createDiginetFingerprint(arrayToCreateFingerprint: Array<string | number>): number {
//         // Concatenate all array values into a single string
//         const str = arrayToCreateFingerprint.join('')
//         // Return the fingerprint as a decimal number
//         return parseInt(this.fnv1a_32(str).toString(16), 16) // Convert hash to hexadecimal then back to decimal
//     }
// }
const getForeignObjects = (cellValue) => {
    const foreignObject = cellValue;
    const foreignObjects = cellValue;
    const isStr = (0, exports.smartTypeOf)(foreignObject) == 'string';
    if (isStr)
        return [];
    const merged = !Array.isArray(foreignObjects) ? [foreignObject] : foreignObjects;
    return merged;
};
exports.getForeignObjects = getForeignObjects;
//# sourceMappingURL=Functions.js.map