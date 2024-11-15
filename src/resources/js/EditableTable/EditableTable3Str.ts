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
