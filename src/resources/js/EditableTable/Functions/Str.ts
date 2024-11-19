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
}
// Usage
// console.log(Str.toHeadline('hello_world_test')) // "Hello World Test"
// console.log(Str.toHeadline('another example_string')) // "Another Example String"
