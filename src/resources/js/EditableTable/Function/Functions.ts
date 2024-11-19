import { TableCellType } from '../Type/EditableTable3DataLineType'

export class Str {
    static toHeadline = (text: string | number): string => {
        return text
            .toString()
            .toLowerCase()
            .split(/[\s_]+/) // Split by spaces or underscores
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
    }

    static makeId = (n: number | string) => {
        if (!n) return ''
        if (typeof n == 'string') return n
        const strPad = String(n).padStart(6, '0')
        return `#${strPad.substring(0, 3)}.${strPad.substring(3)}`
    }
}
// Usage
// console.log(Str.toHeadline('hello_world_test')) // "Hello World Test"
// console.log(Str.toHeadline('another example_string')) // "Another Example String"

export const isObject = (variable: any): variable is Record<string, unknown> => {
    return variable !== null && typeof variable === 'object' && !Array.isArray(variable)
}
//    Usage
// console.log(isObject({}));             // true
// console.log(isObject(null));           // false
// console.log(isObject([]));             // false
// console.log(isObject("hello"));        // false
// console.log(isObject(123));            // false
// console.log(isObject({ key: "value" })); // true

export const smartTypeOf = (variable: any): string => {
    if (Array.isArray(variable)) return 'array'
    if (isObject(variable)) return 'object'
    return typeof variable
}

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
