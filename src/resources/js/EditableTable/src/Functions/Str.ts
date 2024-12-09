import { Pluralize } from './Pluralizer'

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

    static pluralize = (singular: string, count: number): string => {
        return Pluralize.pluralize(singular, count)
    }

    static humanReadable = (count: number): string => {
        // const suffix = count === 1 ? 'item' : 'items'
        if (count >= 1_000_000) {
            return `${(count / 1_000_000).toFixed(1).replace(/\.0$/, '')}M`
        } else if (count >= 1_000) {
            return `${(count / 1_000).toFixed(1).replace(/\.0$/, '')}k`
        }
        return count.toString()
    }

    // Example Usage
    // console.log(formatItemsCount(123456)); // Output: "123k items"
    // console.log(formatItemsCount(1234));   // Output: "1.2k items"
    // console.log(formatItemsCount(12));     // Output: "12 items"
    // console.log(formatItemsCount(1_234_567)); // Output: "1.2M items"
}
// Usage
// console.log(Str.toHeadline('hello_world_test')) // "Hello World Test"
// console.log(Str.toHeadline('another example_string')) // "Another Example String"
